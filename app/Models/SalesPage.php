<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesPage extends Model
{
    use HasFactory;

    public const STATUS_PENDING    = 'pending';
    public const STATUS_GENERATING = 'generating';
    public const STATUS_COMPLETED  = 'completed';
    public const STATUS_FAILED     = 'failed';

    public const TEMPLATES = [
        'aurora'  => ['name' => 'Aurora',  'tagline' => 'Dark luxe with glowing gradients'],
        'minimal' => ['name' => 'Minimal', 'tagline' => 'Clean editorial typography'],
        'bold'    => ['name' => 'Bold',    'tagline' => 'High-contrast brutalist energy'],
    ];

    protected $fillable = [
        'user_id',
        'product_name',
        'description',
        'features',
        'target_audience',
        'price',
        'usp',
        'generated_content',
        'status',
        'failure_reason',
        'template',
    ];

    protected $casts = [
        'features'          => 'array',
        'generated_content' => 'array',
        'price'             => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isGenerated(): bool
    {
        return ! empty($this->generated_content);
    }

    public function isProcessing(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_GENERATING], true);
    }

    public function hasFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function templateKey(): string
    {
        return array_key_exists($this->template, self::TEMPLATES) ? $this->template : 'aurora';
    }
}
