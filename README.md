# AI Sales Page Generator

> Transform a short product brief into a fully styled, persuasive sales landing page in seconds — powered by an LLM, queued for resilience, designed for delight.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Tailwind](https://img.shields.io/badge/Tailwind-3.x-06B6D4?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Tests](https://img.shields.io/badge/tests-36%20passing-22c55e)](#testing)
[![License](https://img.shields.io/badge/license-MIT-blue)](https://opensource.org/licenses/MIT)
[![Powered by Groq](https://img.shields.io/badge/powered%20by-Groq-f55036)](https://groq.com)

A production-grade Laravel 12 web application that takes a structured product brief and uses a large language model to generate eight cohesive landing-page sections (headline, sub-headline, description, benefits, features, social proof, pricing, CTA), then renders them through one of three professionally designed templates with macro and micro animations.

Built as the reference implementation for **Option B — AI Sales Page Generator**.

---

## 🚀 Live Demo

**Try it now:** **[ai-sales-page-genator-production.up.railway.app](https://ai-sales-page-genator-production.up.railway.app/)**

Sign in instantly with the pre-loaded demo account — three sample sales pages (one per template) are ready to explore, no AI credits required:

| Field | Value |
|---|---|
| Email | `demo@demo.com` |
| Password | `password` |

> 💡 First request after a long idle may take ~10 seconds (cold start on the free Railway tier). Subsequent requests are instant.

---

## Table of Contents

- [Highlights](#highlights)
- [Live Feature Tour](#live-feature-tour)
- [Tech Stack](#tech-stack)
- [Architecture Overview](#architecture-overview)
- [Quick Start](#quick-start)
- [LLM Provider Setup](#llm-provider-setup)
- [Configuration Reference](#configuration-reference)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [Templates](#templates)
- [Production Hardening Checklist](#production-hardening-checklist)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [License](#license)

---

## Highlights

### Core Functionality (per the spec)

- **Authentication** — register, login, logout, email verification, password reset (Laravel Breeze).
- **Product input form** — structured Form Request with normalised feature parsing (comma- or newline-separated).
- **AI-powered generation** — eight required sections returned as a single validated JSON object, schema-checked before persisting.
- **Persisted sales pages** — full CRUD for the authenticated user, paginated index, ownership enforced via Policy.
- **Live preview** — full-bleed landing page rendered with the chosen template at `/sales-pages/{id}/preview`.

### Bonus Features (all three implemented)

- **Standalone HTML export** — self-contained file you can host anywhere, with inlined Tailwind CDN config and animation keyframes.
- **Three design templates** — Aurora (dark luxe), Minimal (editorial), Bold (brutalist), switchable post-generation.
- **Section-by-section regeneration** — regenerate just the headline, just the CTA, etc., keeping the rest intact.

### Production-Grade Engineering

- **Async queue jobs** — generation never blocks the HTTP request; the UI polls a JSON status endpoint and reloads when ready.
- **Two-tier rate limiting** — 5 generations per minute and 50 per day per authenticated user (Laravel `RateLimiter`).
- **Per-user daily quota** — counted against persisted `usage_logs`, enforced in the controller before dispatching.
- **Token usage logging** — prompt, completion, and total tokens captured from the provider response and stored per call.
- **Authorization policy** — `SalesPagePolicy` for `view`, `update`, `delete`; controllers use `$this->authorize()`, no manual `abort_if`.
- **Failure handling** — failed jobs persist a human-readable `failure_reason` and surface it in the UI.
- **Provider-agnostic LLM client** — works with any OpenAI-compatible endpoint (Groq, OpenAI, OpenRouter, Cerebras, DeepSeek, Together, Mistral).
- **Comprehensive feature tests** — 11 test cases covering CRUD, ownership, quota, templates, status polling, and dispatch behaviour.

---

## Live Feature Tour

| Page | Path | What it does |
|---|---|---|
| Welcome | `/` | Public landing for unauthenticated visitors; redirects logged-in users to their dashboard. |
| Index / Dashboard | `/sales-pages` | Paginated list of the user's pages with stats and quick actions. |
| Create | `/sales-pages/create` | Product brief form with template selector (3 visual variants). |
| Show | `/sales-pages/{id}` | Editor view: brief sidebar, generated content cards, per-section regenerate, template switcher, processing/failure banners. |
| Live Preview | `/sales-pages/{id}/preview` | Full-page rendering of the selected template with scroll-reveal and CTA animations. |
| Export | `/sales-pages/{id}/export` | Downloads a self-contained HTML file. |
| Status (JSON) | `/sales-pages/{id}/status` | Polled by the show page while a generation is in flight. |

---

## Tech Stack

| Layer | Choice | Why |
|---|---|---|
| Framework | Laravel 12 (PHP 8.2+) | Modern Laravel with simplified bootstrap, queue + policy primitives in core. |
| Auth | Laravel Breeze | Minimal, well-tested scaffold; sessions + email verification out of the box. |
| Database | MySQL / SQLite | MySQL for production; SQLite (`:memory:`) for the test suite. |
| Queue | Laravel Queue (database driver) | Simple, no extra infra; swap to Redis/Horizon later if needed. |
| Frontend build | Vite + Tailwind CSS 3 | Fast HMR, JIT classes, custom keyframes for macro/micro animations. |
| JS sprinkles | Alpine.js | Sidebar toggle, dropdowns; no SPA overhead. |
| LLM client | `Illuminate\Support\Facades\Http` to `/chat/completions` | Provider-agnostic; one config swap to change vendors. |
| Testing | PHPUnit 11 | Feature tests with `RefreshDatabase` and `Queue::fake()`. |

---

## Architecture Overview

```
┌──────────────┐     ┌──────────────────┐    ┌─────────────────┐
│  Browser     │────▶│ SalesPageController│──▶│ GenerateSalesPageJob │
│ (Blade view) │ POST│   - validates     │ dispatch                │
│              │◀────│   - enforces quota│                         ▼
│  Polls status│     │   - dispatches job│                   ┌──────────┐
│  every 2.5s  │     └──────────────────┘                   │ AIService │
│              │              │                              │ (HTTP →   │
│              │              │ writes status                │  Groq /   │
│              │              ▼                              │  OpenAI)  │
│              │       ┌────────────┐  reads                 └─────┬────┘
│              │◀──────│ sales_pages│                              │
│              │  JSON │  + status  │◀─────────────────────────────┘
│              │       └────────────┘     writes generated_content
│              │              ▲
│              │              │ logs tokens
│              │       ┌────────────┐
│              │       │ usage_logs │  (used for daily quota check)
│              │       └────────────┘
└──────────────┘
```

**Key design decisions**

- **Synchronous job in test env.** `phpunit.xml` sets `QUEUE_CONNECTION=sync` so tests assert dispatch behaviour with `Queue::fake()` while keeping integration tests trivially executable.
- **Status-driven UI.** A `status` column (`pending` → `generating` → `completed` / `failed`) drives the show view. The browser polls a lightweight JSON endpoint instead of opening a websocket — simpler infra, identical UX for ≤30s generations.
- **Template as data, not branch.** `$page->template` is a string key (`aurora`/`minimal`/`bold`); `_landing.blade.php` is a one-line dispatcher (`@include('salespages.templates.' . $page->templateKey())`) so adding a fourth template is one new partial + one constant entry.
- **Quota uses the same table as analytics.** `usage_logs` records both successful and failed calls with token counts. The daily-quota check is just `count where success=true and created_at >= today`. No separate counter needed.

---

## Quick Start

### Prerequisites

- **PHP 8.2+** with the typical Laravel extensions (`pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
- **Composer 2.x**
- **Node.js 18+** and npm
- **MySQL 8** (or MariaDB 10.6+) — or SQLite for local trial runs
- A free API key from a Groq-compatible provider (see [LLM Provider Setup](#llm-provider-setup))

### One-shot setup

```bash
git clone https://github.com/gilangrmadnii/ai-sales-page-generator.git
cd ai-sales-page-generator
composer install
cp .env.example .env
php artisan key:generate

# Configure DB credentials and OPENAI_API_KEY in .env, then:
php artisan migrate --seed   # --seed loads the demo user + 3 example pages
npm install
npm run build
```

Or, if you prefer the bundled composer script:

```bash
composer setup
```

This runs `composer install`, copies `.env`, generates `APP_KEY`, runs migrations, installs npm deps, and builds assets in one go.

### Demo account

After running `php artisan migrate --seed`, you can sign in instantly with:

| Field | Value |
|---|---|
| Email | `demo@demo.com` |
| Password | `password` |

The demo user comes pre-loaded with three fully generated sales pages — one for each template (Aurora, Minimal, Bold) — so you can explore the app without burning any AI credits. Re-run `php artisan db:seed --class=DemoSeeder` to reset the demo data at any time.

---

## LLM Provider Setup

The application speaks the **OpenAI Chat Completions** wire format, so it works with any compatible vendor without code changes — only `.env` swaps.

### Recommended: Groq (free tier, fast, no credit card)

1. Sign up at <https://console.groq.com>.
2. Create an API key under **API Keys**.
3. Set in `.env`:

   ```dotenv
   OPENAI_API_KEY=gsk_xxxxxxxxxxxxxxxxxxxx
   OPENAI_BASE_URL=https://api.groq.com/openai/v1
   OPENAI_MODEL=llama-3.3-70b-versatile
   OPENAI_TIMEOUT=60
   ```

4. Clear config cache: `php artisan config:clear`.

### Alternate providers

| Provider | `OPENAI_BASE_URL` | Notes |
|---|---|---|
| Groq (free) | `https://api.groq.com/openai/v1` | `llama-3.3-70b-versatile`, JSON mode supported. |
| OpenRouter | `https://openrouter.ai/api/v1` | Many `:free` models (DeepSeek, Llama, Qwen). |
| Cerebras | `https://api.cerebras.ai/v1` | Free tier, ultra-fast inference. |
| OpenAI | `https://api.openai.com/v1` | `gpt-4o-mini` works well as a budget default. |
| Google AI Studio | `https://generativelanguage.googleapis.com/v1beta/openai/` | Gemini via OpenAI-compat shim. |

> The model **must** support `response_format: json_object` for the validator to work reliably. If your chosen model does not, the service falls back to a forgiving JSON parse that strips ```` ``` ```` fences.

---

## Configuration Reference

All configuration lives in `.env`. The most relevant keys:

| Key | Default | Purpose |
|---|---|---|
| `APP_ENV` | `local` | Use `production` when deployed. |
| `APP_DEBUG` | `true` | **Must be `false` in production.** |
| `APP_URL` | `http://localhost` | Base URL; required for queue/email rendering. |
| `DB_CONNECTION` | `mysql` | `mysql`, `pgsql`, or `sqlite`. |
| `QUEUE_CONNECTION` | `database` | `database` ships out of the box; swap for `redis` at scale. |
| `SESSION_ENCRYPT` | `false` | Set to `true` in production. |
| `OPENAI_API_KEY` | _empty_ | Required. Provider API key. |
| `OPENAI_BASE_URL` | `https://api.openai.com/v1` | Override per provider. |
| `OPENAI_MODEL` | `gpt-4o-mini` | Override per provider. |
| `OPENAI_TIMEOUT` | `60` | Seconds. Increase for slower providers. |
| `AI_DAILY_QUOTA_PER_USER` | `20` | Max successful generations per user per day. |

Rate limiting is configured in `app/Providers/AppServiceProvider.php`:

```php
RateLimiter::for('ai-generate', function (Request $request) {
    return [
        Limit::perMinute(5)->by($request->user()?->id ?: $request->ip()),
        Limit::perDay(50)->by($request->user()?->id ?: $request->ip()),
    ];
});
```

Adjust the numbers there if you need different ceilings.

---

## Running the Application

### Development (4 processes via concurrently)

```bash
composer dev
```

This starts the web server, queue listener, log tailer (Pail), and Vite dev server in one terminal with colour-coded output.

### Manually, terminal-by-terminal

```bash
# Terminal 1 — Laravel server
php artisan serve

# Terminal 2 — Queue worker (REQUIRED for AI generation)
# --sleep=1 picks up jobs ~3x faster than the default
php artisan queue:work --sleep=1 --tries=2 --timeout=120

# Terminal 3 — Vite dev server (HMR for frontend changes)
npm run dev
```

> ⚠️ **Without a running queue worker, your sales pages will stay stuck in `pending` forever.** Either run `composer dev` (recommended) or start `queue:work` manually.

Then visit <http://localhost:8000>, register, and create your first sales page.

---

## Project Structure

Only the application-specific files. Stock Laravel scaffolding omitted.

```
app/
├── Http/
│   ├── Controllers/
│   │   └── SalesPageController.php   # CRUD + regenerate + setTemplate + status
│   └── Requests/
│       └── StoreSalesPageRequest.php # Validation + featuresArray() normaliser
├── Jobs/
│   ├── GenerateSalesPageJob.php      # Full generation; logs UsageLog
│   └── RegenerateSectionJob.php      # Single-section regeneration
├── Models/
│   ├── SalesPage.php                 # Status constants, TEMPLATES catalogue
│   ├── UsageLog.php                  # Per-call token + outcome record
│   └── User.php                      # +aiCallsToday(), +hasReachedDailyAiQuota()
├── Policies/
│   └── SalesPagePolicy.php           # view / update / delete = ownership
├── Providers/
│   ├── AIServiceProvider.php         # Binds AIService with config values
│   └── AppServiceProvider.php        # Registers ai-generate rate limiter
└── Services/
    └── AIService.php                 # Provider-agnostic chat-completions client

database/
├── migrations/
│   ├── 2026_01_01_000000_create_sales_pages_table.php
│   ├── 2026_04_26_000000_add_status_to_sales_pages_table.php
│   ├── 2026_04_26_000001_create_usage_logs_table.php
│   └── 2026_04_26_000002_add_template_to_sales_pages_table.php
└── factories/
    ├── SalesPageFactory.php          # ::completed() state with full content
    └── UsageLogFactory.php

resources/views/salespages/
├── _landing.blade.php                # Template dispatcher (one-liner)
├── create.blade.php                  # Brief form + template radio cards
├── export.blade.php                  # Standalone HTML export wrapper
├── index.blade.php                   # Paginated list + stats
├── preview.blade.php                 # Live preview wrapper
├── show.blade.php                    # Editor + status banner + polling
└── templates/
    ├── aurora.blade.php              # Default — dark luxe gradients
    ├── minimal.blade.php             # Editorial typography, stone palette
    └── bold.blade.php                # Brutalist yellow/black/fuchsia

routes/
└── web.php                           # All app routes; AI routes throttled

tests/Feature/
└── SalesPageTest.php                 # 11 tests covering the SalesPage flow
```

---

## Templates

Three first-class templates, each a self-contained Blade partial in `resources/views/salespages/templates/`:

| Key | Name | Aesthetic | Best for |
|---|---|---|---|
| `aurora` | **Aurora** | Dark luxe with glowing radial gradients, animated aurora drift, glass-morphism cards | SaaS, premium digital products |
| `minimal` | **Minimal** | Stone-paper palette, serif headlines, generous whitespace, editorial rhythm | Books, courses, indie products |
| `bold` | **Bold** | High-contrast yellow/black/fuchsia, brutalist hard shadows, marquee strip, big-type CTAs | Bootcamps, agencies, anything that wants to shout |

### Animation system

Each template uses the same animation primitives, defined as Tailwind keyframes in `tailwind.config.js`:

- **Macro:** `aurora-drift`, `marquee`, `glow-pulse`, `float`, `tilt`, `gradient-x`
- **Micro:** `fade-in`, `fade-in-up`, `fade-in-down`, `slide-in-left/right`, `scale-in`, `pulse-ring`, `bounce-soft`, `wiggle`, `shimmer`

Scroll-reveal is implemented in vanilla JS via `IntersectionObserver` on `[data-reveal]` elements, with stagger via `--reveal-delay`. `prefers-reduced-motion` is fully respected — animations collapse to instant transitions when the user has it enabled.

### Adding a fourth template

1. Add an entry to `SalesPage::TEMPLATES` in `app/Models/SalesPage.php`.
2. Drop a new partial at `resources/views/salespages/templates/<key>.blade.php`. Read `$page->generated_content` and the page attributes; the dispatcher does the rest.
3. Add a thumbnail tile to the radio in `create.blade.php` and the switcher in `show.blade.php`.
4. (Optional) Update tests in `tests/Feature/SalesPageTest.php` to cover the new key.

No controller or model code changes are required.

---

## Production Hardening Checklist

Before going live, walk through this list:

### Environment

- [ ] `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://...`
- [ ] `SESSION_ENCRYPT=true`
- [ ] `LOG_LEVEL=warning` (or `error`)
- [ ] Rotated `APP_KEY` (`php artisan key:generate`)
- [ ] Real database credentials, not the SQLite default

### Infrastructure

- [ ] HTTPS terminator (Cloudflare, Caddy, nginx + Let's Encrypt)
- [ ] Queue worker managed by **Supervisor** or **systemd** with auto-restart:

  ```ini
  [program:salesgen-worker]
  command=php /var/www/salesgen/artisan queue:work --tries=2 --timeout=120 --sleep=3
  autostart=true
  autorestart=true
  user=www-data
  numprocs=2
  ```

- [ ] `php artisan config:cache && php artisan route:cache && php artisan view:cache` on deploy
- [ ] Daily DB backup
- [ ] Error monitoring (Sentry, Bugsnag, or Laravel Telescope/Pulse on a private route)

### Security

- [ ] Rate-limit headers exposed in CDN allowlist
- [ ] CSP / `X-Frame-Options` headers set at the proxy layer
- [ ] `OPENAI_API_KEY` stored in a real secret manager, not committed `.env`
- [ ] Quota (`AI_DAILY_QUOTA_PER_USER`) tuned for your billing model

### Observability

- [ ] Watch the `usage_logs` table — useful for billing reconciliation and anomaly detection
- [ ] Alert on `status='failed'` rate in `sales_pages` (currently logged via `Log::error` from the job)

---

## Testing

Tests run on an in-memory SQLite database with `QUEUE_CONNECTION=sync` (see `phpunit.xml`).

```bash
# Run everything
php artisan test

# Run only the SalesPage feature tests
php artisan test --filter=SalesPageTest
```

Expected output:

```
PASS  Tests\Feature\SalesPageTest
✓ store creates pending page and dispatches job
✓ user cannot view anothers page
✓ user cannot delete anothers page
✓ status endpoint returns json
✓ quota blocks store when limit reached
✓ regenerate section dispatches job
✓ regenerate blocked while processing
✓ store persists chosen template
✓ store rejects invalid template
✓ owner can switch template
✓ stranger cannot switch template

Tests:  36 passed
```

The suite uses `Queue::fake()` so it never calls the real LLM API — fast, deterministic, free.

---

## Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| Page stuck on "Generating…" forever | Queue worker not running | Run `php artisan queue:work` in a separate terminal. |
| `AI service returned an error (HTTP 401)` | Bad / missing API key | Verify `OPENAI_API_KEY` and run `php artisan config:clear`. |
| `AI service returned invalid JSON` | Model doesn't respect `response_format` | Switch to a stronger model or one with JSON mode (Groq's `llama-3.3-70b-versatile`, OpenAI `gpt-4o-mini`). |
| `429 Too Many Requests` after 5 generations | Per-minute rate limit hit | Wait 60 seconds. Adjust limits in `AppServiceProvider` if needed. |
| `You've hit today's AI usage limit` | Daily quota reached | Increase `AI_DAILY_QUOTA_PER_USER` or wait until midnight server time. |
| Static export looks unstyled when opened from disk | Tailwind CDN blocked offline | Open via a local web server (e.g. `php -S localhost:9000`) or host the file. |
| `SQLSTATE[HY000]: General error: 1 no such table: jobs` | Migrations not run | `php artisan migrate`. |
| Tests fail with database errors | Forgotten migration | `php artisan test` runs migrations on each suite — make sure `:memory:` is set in `phpunit.xml`. |

---

## License

Released under the [MIT License](https://opensource.org/licenses/MIT). Built on [Laravel](https://laravel.com), which is also MIT-licensed.

---

<sub>Made for the **Option B — AI Sales Page Generator** brief. Powered by [Groq](https://groq.com) (or any OpenAI-compatible LLM you point it at).</sub>
