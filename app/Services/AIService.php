<?php

namespace App\Services;

use App\Models\SalesPage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AIService
{
    private const REQUIRED_KEYS = [
        'headline',
        'subheadline',
        'description',
        'benefits',
        'features',
        'social_proof',
        'pricing',
        'cta',
    ];

    /** @var array{prompt_tokens:int,completion_tokens:int,total_tokens:int}|null */
    private ?array $lastUsage = null;

    private readonly string $apiKey;
    private readonly string $baseUrl;
    private readonly string $model;

    public function __construct(
        string $apiKey,
        string $baseUrl,
        string $model,
        private readonly int $timeout = 60,
    ) {
        // Trim whitespace defensively — env values copy-pasted from dashboards
        // often pick up trailing spaces or newlines that break URLs.
        $this->apiKey  = trim($apiKey);
        $this->baseUrl = trim($baseUrl);
        $this->model   = trim($model);
    }

    public function lastUsage(): ?array
    {
        return $this->lastUsage;
    }

    public function model(): string
    {
        return $this->model;
    }

    /**
     * Generate a full sales page payload for the given product input.
     *
     * @param  array<string, mixed>  $input  product_name, description, features[], target_audience, price, usp
     * @return array<string, mixed> structured JSON keyed by REQUIRED_KEYS
     */
    public function generateSalesPage(array $input): array
    {
        $prompt = $this->buildPrompt($input);

        $payload = $this->callApi([
            ['role' => 'system', 'content' => $this->systemPrompt()],
            ['role' => 'user',   'content' => $prompt],
        ]);

        return $this->parseAndValidate($payload);
    }

    /**
     * Regenerate a single section of an existing sales page.
     */
    public function regenerateSection(SalesPage $page, string $section): array
    {
        if (! in_array($section, self::REQUIRED_KEYS, true)) {
            throw new \InvalidArgumentException("Unknown section: {$section}");
        }

        $context = [
            'product_name'     => $page->product_name,
            'description'      => $page->description,
            'features'         => $page->features,
            'target_audience'  => $page->target_audience,
            'price'            => $page->price,
            'usp'              => $page->usp,
            'current_content'  => $page->generated_content,
        ];

        $prompt = "Regenerate ONLY the `{$section}` field for the following product, keeping the same JSON schema "
                . "but returning ONLY a JSON object with the single key `{$section}`. Be more compelling than the "
                . "current version.\n\nContext:\n" . json_encode($context, JSON_PRETTY_PRINT);

        $payload = $this->callApi([
            ['role' => 'system', 'content' => $this->systemPrompt()],
            ['role' => 'user',   'content' => $prompt],
        ]);

        $decoded = $this->decodeJson($payload);

        if (! array_key_exists($section, $decoded)) {
            throw new RuntimeException("AI response missing requested section: {$section}");
        }

        return [$section => $decoded[$section]];
    }

    private function systemPrompt(): string
    {
        return <<<'PROMPT'
You are an elite direct-response copywriter trained in the styles of David Ogilvy, Gary Halbert, and modern SaaS landing-page experts.

You write persuasive, benefit-driven sales copy that:
- Leads with a clear, specific value proposition
- Speaks directly to the target audience's pain and aspiration
- Uses concrete language (numbers, outcomes) over vague claims
- Maintains a confident, friendly, modern marketing tone

CRITICAL OUTPUT RULES:
- Respond with a SINGLE valid JSON object.
- No markdown fences, no commentary, no preamble — JSON only.
- All string values must be plain text (no HTML, no markdown).
- Arrays must contain plain strings.
PROMPT;
    }

    private function buildPrompt(array $input): string
    {
        $features = is_array($input['features'] ?? null)
            ? implode("\n- ", $input['features'])
            : (string) ($input['features'] ?? '');

        $price = $input['price'] ?? 'not specified';
        $usp   = $input['usp'] ?? 'not specified';

        return <<<PROMPT
Generate a high-converting sales landing page for this product.

PRODUCT
- Name: {$input['product_name']}
- Description: {$input['description']}
- Target Audience: {$input['target_audience']}
- Price: {$price}
- Unique Selling Points: {$usp}

KEY FEATURES
- {$features}

Return a JSON object with EXACTLY these keys:
{
  "headline":      "string — 6 to 12 words, hook the reader's biggest desire or pain",
  "subheadline":   "string — 1 sentence expanding the headline with a specific outcome",
  "description":   "string — 2 to 3 short paragraphs of persuasive body copy",
  "benefits":      ["string", ...] — 4 to 6 outcome-focused benefits (not features),
  "features":      ["string", ...] — 4 to 8 concrete features rewritten in marketing voice,
  "social_proof":  "string — a short credibility line (testimonial-style, customer count, or trust signal)",
  "pricing":       "string — pricing presentation with anchor + value framing",
  "cta":           "string — 2 to 5 word action button label"
}

Return ONLY the JSON object. No prose, no markdown, no code fences.
PROMPT;
    }

    /**
     * Low-level chat completion call (OpenAI-compatible).
     */
    private function callApi(array $messages): string
    {
        if ($this->apiKey === '') {
            throw new RuntimeException('AI API key is not configured. Set OPENAI_API_KEY in your .env file.');
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withToken($this->apiKey)
                ->acceptJson()
                ->asJson()
                ->post(rtrim($this->baseUrl, '/') . '/chat/completions', [
                    'model'           => $this->model,
                    'temperature'     => 0.8,
                    'max_tokens'      => 2000,
                    'response_format' => ['type' => 'json_object'],
                    'messages'        => $messages,
                ]);
        } catch (\Throwable $e) {
            Log::error('AIService: HTTP transport error', ['error' => $e->getMessage()]);
            throw new RuntimeException('Could not reach the AI service. Please try again.', 0, $e);
        }

        if ($response->failed()) {
            Log::error('AIService: API returned error', [
                'status'   => $response->status(),
                'body'     => $response->body(),
                'url'      => rtrim($this->baseUrl, '/') . '/chat/completions',
                'model'    => $this->model,
                'key_head' => substr($this->apiKey, 0, 8) . '...',
            ]);
            throw new RuntimeException('AI service returned an error (HTTP ' . $response->status() . ').');
        }

        $content = $response->json('choices.0.message.content');

        if (! is_string($content) || $content === '') {
            throw new RuntimeException('AI service returned an empty response.');
        }

        $this->lastUsage = [
            'prompt_tokens'     => (int) $response->json('usage.prompt_tokens', 0),
            'completion_tokens' => (int) $response->json('usage.completion_tokens', 0),
            'total_tokens'      => (int) $response->json('usage.total_tokens', 0),
        ];

        return $content;
    }

    /**
     * Decode a JSON string and tolerate accidental code fences from weaker models.
     */
    private function decodeJson(string $raw): array
    {
        $clean = trim($raw);
        $clean = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $clean) ?? $clean;

        $decoded = json_decode($clean, true);

        if (! is_array($decoded)) {
            Log::warning('AIService: invalid JSON from AI', ['raw' => $raw]);
            throw new RuntimeException('AI service returned invalid JSON.');
        }

        return $decoded;
    }

    private function parseAndValidate(string $raw): array
    {
        $decoded = $this->decodeJson($raw);

        $missing = array_diff(self::REQUIRED_KEYS, array_keys($decoded));
        if ($missing !== []) {
            throw new RuntimeException('AI response missing keys: ' . implode(', ', $missing));
        }

        // Coerce array fields defensively.
        foreach (['benefits', 'features'] as $listKey) {
            if (! is_array($decoded[$listKey])) {
                $decoded[$listKey] = array_filter(array_map('trim', explode("\n", (string) $decoded[$listKey])));
            }
        }

        return $decoded;
    }
}
