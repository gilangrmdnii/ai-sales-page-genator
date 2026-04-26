<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageLog extends Model
{
    use HasFactory;

    public const ACTION_GENERATE = 'generate';
    public const ACTION_REGENERATE_SECTION = 'regenerate_section';

    protected $fillable = [
        'user_id',
        'sales_page_id',
        'action',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'model',
        'success',
    ];

    protected $casts = [
        'success' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salesPage(): BelongsTo
    {
        return $this->belongsTo(SalesPage::class);
    }
}
