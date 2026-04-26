<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesPageRequest;
use App\Jobs\GenerateSalesPageJob;
use App\Jobs\RegenerateSectionJob;
use App\Models\SalesPage;
use App\Models\UsageLog;
use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SalesPageController extends Controller
{
    private function isSyncQueue(): bool
    {
        return config('queue.default') === 'sync';
    }

    public function index(Request $request): View
    {
        $pages = SalesPage::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('salespages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('salespages.create');
    }

    public function store(StoreSalesPageRequest $request, AIService $ai): RedirectResponse
    {
        if ($request->user()->hasReachedDailyAiQuota()) {
            return back()
                ->withInput()
                ->with('error', $this->quotaMessage());
        }

        $page = SalesPage::create([
            'user_id'         => $request->user()->id,
            'product_name'    => $request->string('product_name'),
            'description'     => $request->string('description'),
            'features'        => $request->featuresArray(),
            'target_audience' => $request->string('target_audience'),
            'price'           => $request->input('price'),
            'usp'             => $request->input('usp'),
            'template'        => $request->input('template', 'aurora'),
            'status'          => SalesPage::STATUS_PENDING,
        ]);

        if ($this->isSyncQueue()) {
            $this->generateInline($page, $ai);

            return redirect()->route('sales-pages.show', $page)->with(
                $page->hasFailed() ? 'error' : 'success',
                $page->hasFailed()
                    ? 'Generation failed: ' . $page->failure_reason
                    : 'Sales page ready.'
            );
        }

        GenerateSalesPageJob::dispatch($page);

        return redirect()
            ->route('sales-pages.show', $page)
            ->with('success', 'Generating your sales page — this usually takes a few seconds.');
    }

    /**
     * Run AI generation inline (used when QUEUE_CONNECTION=sync to skip job overhead).
     * Single DB write on success/failure instead of three.
     */
    private function generateInline(SalesPage $page, AIService $ai): void
    {
        try {
            $generated = $ai->generateSalesPage([
                'product_name'    => $page->product_name,
                'description'     => $page->description,
                'features'        => $page->features,
                'target_audience' => $page->target_audience,
                'price'           => $page->price,
                'usp'             => $page->usp,
            ]);

            $page->update([
                'generated_content' => $generated,
                'status'            => SalesPage::STATUS_COMPLETED,
                'failure_reason'    => null,
            ]);

            $usage = $ai->lastUsage() ?? [];
            UsageLog::create([
                'user_id'           => $page->user_id,
                'sales_page_id'     => $page->id,
                'action'            => UsageLog::ACTION_GENERATE,
                'prompt_tokens'     => $usage['prompt_tokens']     ?? 0,
                'completion_tokens' => $usage['completion_tokens'] ?? 0,
                'total_tokens'      => $usage['total_tokens']      ?? 0,
                'model'             => $ai->model(),
                'success'           => true,
            ]);
        } catch (Throwable $e) {
            Log::error('Inline generation failed', ['page_id' => $page->id, 'error' => $e->getMessage()]);

            $page->update([
                'status'         => SalesPage::STATUS_FAILED,
                'failure_reason' => $e->getMessage(),
            ]);

            UsageLog::create([
                'user_id'       => $page->user_id,
                'sales_page_id' => $page->id,
                'action'        => UsageLog::ACTION_GENERATE,
                'success'       => false,
            ]);
        }
    }

    /**
     * Run section regeneration inline (sync mode).
     */
    private function regenerateSectionInline(SalesPage $page, string $section, AIService $ai): void
    {
        try {
            $patch = $ai->regenerateSection($page, $section);

            $page->update([
                'generated_content' => array_merge($page->generated_content ?? [], $patch),
                'status'            => SalesPage::STATUS_COMPLETED,
                'failure_reason'    => null,
            ]);

            $usage = $ai->lastUsage() ?? [];
            UsageLog::create([
                'user_id'           => $page->user_id,
                'sales_page_id'     => $page->id,
                'action'            => UsageLog::ACTION_REGENERATE_SECTION,
                'prompt_tokens'     => $usage['prompt_tokens']     ?? 0,
                'completion_tokens' => $usage['completion_tokens'] ?? 0,
                'total_tokens'      => $usage['total_tokens']      ?? 0,
                'model'             => $ai->model(),
                'success'           => true,
            ]);
        } catch (Throwable $e) {
            Log::error('Inline section regeneration failed', ['page_id' => $page->id, 'section' => $section, 'error' => $e->getMessage()]);

            $page->update([
                'status'         => SalesPage::STATUS_FAILED,
                'failure_reason' => $e->getMessage(),
            ]);

            UsageLog::create([
                'user_id'       => $page->user_id,
                'sales_page_id' => $page->id,
                'action'        => UsageLog::ACTION_REGENERATE_SECTION,
                'success'       => false,
            ]);
        }
    }

    public function show(Request $request, SalesPage $salesPage): View
    {
        $this->authorize('view', $salesPage);

        return view('salespages.show', ['page' => $salesPage]);
    }

    public function preview(Request $request, SalesPage $salesPage): View
    {
        $this->authorize('view', $salesPage);

        abort_unless($salesPage->isGenerated(), 404, 'No generated content yet.');

        return view('salespages.preview', ['page' => $salesPage]);
    }

    public function destroy(Request $request, SalesPage $salesPage): RedirectResponse
    {
        $this->authorize('delete', $salesPage);

        $salesPage->delete();

        return redirect()
            ->route('sales-pages.index')
            ->with('success', 'Sales page deleted.');
    }

    public function regenerate(Request $request, SalesPage $salesPage, AIService $ai): RedirectResponse
    {
        $this->authorize('update', $salesPage);

        if ($salesPage->isProcessing()) {
            return back()->with('error', 'A generation is already in progress for this page.');
        }

        if ($request->user()->hasReachedDailyAiQuota()) {
            return back()->with('error', $this->quotaMessage());
        }

        if ($this->isSyncQueue()) {
            $this->generateInline($salesPage, $ai);

            return back()->with(
                $salesPage->hasFailed() ? 'error' : 'success',
                $salesPage->hasFailed()
                    ? 'Regeneration failed: ' . $salesPage->failure_reason
                    : 'Sales page regenerated.'
            );
        }

        $salesPage->update(['status' => SalesPage::STATUS_PENDING, 'failure_reason' => null]);
        GenerateSalesPageJob::dispatch($salesPage);

        return back()->with('success', 'Regenerating — this usually takes a few seconds.');
    }

    public function regenerateSection(Request $request, SalesPage $salesPage, AIService $ai): RedirectResponse
    {
        $this->authorize('update', $salesPage);

        $data = $request->validate([
            'section' => ['required', 'string', 'in:headline,subheadline,description,benefits,features,social_proof,pricing,cta'],
        ]);

        if ($salesPage->isProcessing()) {
            return back()->with('error', 'A generation is already in progress for this page.');
        }

        if ($request->user()->hasReachedDailyAiQuota()) {
            return back()->with('error', $this->quotaMessage());
        }

        if ($this->isSyncQueue()) {
            $this->regenerateSectionInline($salesPage, $data['section'], $ai);

            return back()->with(
                $salesPage->hasFailed() ? 'error' : 'success',
                $salesPage->hasFailed()
                    ? 'Section regeneration failed: ' . $salesPage->failure_reason
                    : "Section `{$data['section']}` regenerated."
            );
        }

        $salesPage->update(['status' => SalesPage::STATUS_PENDING, 'failure_reason' => null]);
        RegenerateSectionJob::dispatch($salesPage, $data['section']);

        return back()->with('success', "Regenerating `{$data['section']}` — refresh in a few seconds.");
    }

    private function quotaMessage(): string
    {
        $limit = (int) config('services.ai_quota.daily_per_user', 20);

        return "You've hit today's AI usage limit ({$limit} generations). Try again tomorrow.";
    }

    public function setTemplate(Request $request, SalesPage $salesPage): RedirectResponse
    {
        $this->authorize('update', $salesPage);

        $data = $request->validate([
            'template' => ['required', 'string', 'in:' . implode(',', array_keys(SalesPage::TEMPLATES))],
        ]);

        $salesPage->update(['template' => $data['template']]);

        return back()->with('success', 'Template updated to ' . SalesPage::TEMPLATES[$data['template']]['name'] . '.');
    }

    public function status(Request $request, SalesPage $salesPage): JsonResponse
    {
        $this->authorize('view', $salesPage);

        return response()->json([
            'status'         => $salesPage->status,
            'is_generated'   => $salesPage->isGenerated(),
            'failure_reason' => $salesPage->failure_reason,
        ]);
    }

    /**
     * Export the live preview as a self-contained HTML file.
     */
    public function exportHtml(Request $request, SalesPage $salesPage): Response
    {
        $this->authorize('view', $salesPage);
        abort_unless($salesPage->isGenerated(), 404, 'No generated content yet.');

        $html = view('salespages.export', ['page' => $salesPage])->render();
        $filename = \Illuminate\Support\Str::slug($salesPage->product_name) . '.html';

        return response($html, 200, [
            'Content-Type'        => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

}
