<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesPageController;
use App\Services\AIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('sales-pages.index')
        : view('welcome');
});

// TEMPORARY DEBUG ENDPOINT — remove before final submission
Route::get('/__debug/ai', function () {
    $key = (string) config('services.openai.key');
    $base = (string) config('services.openai.base_url');
    $model = (string) config('services.openai.model');

    $url = rtrim($base, '/') . '/chat/completions';

    try {
        $res = Http::timeout(15)
            ->withToken($key)
            ->acceptJson()
            ->asJson()
            ->post($url, [
                'model'      => $model,
                'max_tokens' => 5,
                'messages'   => [['role' => 'user', 'content' => 'Reply OK']],
            ]);

        return response()->json([
            'config' => [
                'base_url'  => $base,
                'model'     => $model,
                'key_first' => substr($key, 0, 8),
                'key_last'  => substr($key, -4),
                'key_len'   => strlen($key),
                'computed_url' => $url,
            ],
            'response' => [
                'status' => $res->status(),
                'ok'     => $res->successful(),
                'body'   => $res->body(),
            ],
        ], 200, [], JSON_PRETTY_PRINT);
    } catch (\Throwable $e) {
        return response()->json([
            'config' => [
                'base_url'  => $base,
                'model'     => $model,
                'key_first' => substr($key, 0, 8),
                'key_last'  => substr($key, -4),
                'key_len'   => strlen($key),
                'computed_url' => $url,
            ],
            'exception' => $e->getMessage(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('sales-pages.index'))->name('dashboard');

    // Sales pages CRUD
    Route::get('/sales-pages',               [SalesPageController::class, 'index'])->name('sales-pages.index');
    Route::get('/sales-pages/create',        [SalesPageController::class, 'create'])->name('sales-pages.create');
    Route::post('/sales-pages',              [SalesPageController::class, 'store'])->middleware('throttle:ai-generate')->name('sales-pages.store');
    Route::get('/sales-pages/{salesPage}',   [SalesPageController::class, 'show'])->name('sales-pages.show');
    Route::delete('/sales-pages/{salesPage}', [SalesPageController::class, 'destroy'])->name('sales-pages.destroy');

    // Live preview + export
    Route::get('/sales-pages/{salesPage}/preview', [SalesPageController::class, 'preview'])->name('sales-pages.preview');
    Route::get('/sales-pages/{salesPage}/export',  [SalesPageController::class, 'exportHtml'])->name('sales-pages.export');
    Route::get('/sales-pages/{salesPage}/status',  [SalesPageController::class, 'status'])->name('sales-pages.status');
    Route::post('/sales-pages/{salesPage}/template', [SalesPageController::class, 'setTemplate'])->name('sales-pages.set-template');

    // Regeneration
    Route::post('/sales-pages/{salesPage}/regenerate',         [SalesPageController::class, 'regenerate'])->middleware('throttle:ai-generate')->name('sales-pages.regenerate');
    Route::post('/sales-pages/{salesPage}/regenerate-section', [SalesPageController::class, 'regenerateSection'])->middleware('throttle:ai-generate')->name('sales-pages.regenerate-section');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile',     [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',   [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',  [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
