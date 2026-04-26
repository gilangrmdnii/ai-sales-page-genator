<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesPageRequest;
use App\Jobs\GenerateSalesPageJob;
use App\Jobs\RegenerateSectionJob;
use App\Models\SalesPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SalesPageController extends Controller
{

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

    public function store(StoreSalesPageRequest $request): RedirectResponse
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

        GenerateSalesPageJob::dispatch($page);

        return redirect()
            ->route('sales-pages.show', $page)
            ->with('success', 'Generating your sales page — this usually takes a few seconds.');
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

    public function regenerate(Request $request, SalesPage $salesPage): RedirectResponse
    {
        $this->authorize('update', $salesPage);

        if ($salesPage->isProcessing()) {
            return back()->with('error', 'A generation is already in progress for this page.');
        }

        if ($request->user()->hasReachedDailyAiQuota()) {
            return back()->with('error', $this->quotaMessage());
        }

        $salesPage->update(['status' => SalesPage::STATUS_PENDING, 'failure_reason' => null]);
        GenerateSalesPageJob::dispatch($salesPage);

        return back()->with('success', 'Regenerating — this usually takes a few seconds.');
    }

    public function regenerateSection(Request $request, SalesPage $salesPage): RedirectResponse
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
