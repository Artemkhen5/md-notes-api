<?php

namespace App\Domain\Notes;

use App\Models\User;
use App\Services\MarkdownService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'title',
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getHtmlContentAttribute(): string
    {
        return app(MarkdownService::class)->toHtml($this->content);
    }
}
