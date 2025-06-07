<?php

namespace App\Providers;

use App\Domain\Notes\Note;
use App\Domain\Notes\Policies\NotePolicy;
use App\Services\LanguageToolService;
use App\Services\MarkdownService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MarkdownService::class, function ($app) {
            return new MarkdownService();
        });
        $this->app->singleton(LanguageToolService::class, function ($app) {
            return new LanguageToolService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Gate::policy(Note::class, NotePolicy::class);
    }
}
