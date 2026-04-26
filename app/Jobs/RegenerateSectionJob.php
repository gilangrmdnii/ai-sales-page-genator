<?php

namespace App\Jobs;

use App\Models\SalesPage;
use App\Models\UsageLog;
use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegenerateSectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 90;
    public int $backoff = 10;

    public function __construct(public SalesPage $page, public string $section)
    {
    }

    public function handle(AIService $ai): void
    {
        $this->page->update([
            'status'         => SalesPage::STATUS_GENERATING,
            'failure_reason' => null,
        ]);

        $patch = $ai->regenerateSection($this->page, $this->section);

        $this->page->update([
            'generated_content' => array_merge($this->page->generated_content ?? [], $patch),
            'status'            => SalesPage::STATUS_COMPLETED,
            'failure_reason'    => null,
        ]);

        $usage = $ai->lastUsage() ?? [];
        UsageLog::create([
            'user_id'           => $this->page->user_id,
            'sales_page_id'     => $this->page->id,
            'action'            => UsageLog::ACTION_REGENERATE_SECTION,
            'prompt_tokens'     => $usage['prompt_tokens']     ?? 0,
            'completion_tokens' => $usage['completion_tokens'] ?? 0,
            'total_tokens'      => $usage['total_tokens']      ?? 0,
            'model'             => $ai->model(),
            'success'           => true,
        ]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('RegenerateSectionJob failed', [
            'page_id' => $this->page->id,
            'section' => $this->section,
            'error'   => $e->getMessage(),
        ]);

        $this->page->update([
            'status'         => SalesPage::STATUS_FAILED,
            'failure_reason' => $e->getMessage(),
        ]);

        UsageLog::create([
            'user_id'       => $this->page->user_id,
            'sales_page_id' => $this->page->id,
            'action'        => UsageLog::ACTION_REGENERATE_SECTION,
            'success'       => false,
        ]);
    }
}
