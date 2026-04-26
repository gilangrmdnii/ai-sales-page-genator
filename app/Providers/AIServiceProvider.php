<?php

namespace App\Providers;

use App\Services\AIService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AIService::class, function (Application $app) {
            $config = $app['config']->get('services.openai');

            return new AIService(
                apiKey:  (string) ($config['key']      ?? ''),
                baseUrl: (string) ($config['base_url'] ?? 'https://api.openai.com/v1'),
                model:   (string) ($config['model']    ?? 'gpt-4o-mini'),
                timeout: (int)    ($config['timeout']  ?? 60),
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
