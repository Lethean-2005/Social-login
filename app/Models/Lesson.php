<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Lesson extends Model
{
    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'category',
        'excerpt',
        'body',
        'attachment_path',
        'attachment_name',
        'video_url',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->body));
        return max(1, (int) ceil($words / 200));
    }

    public function getRenderedBodyAttribute(): string
    {
        return (string) Str::markdown((string) $this->body);
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment_path ? \Storage::disk('public')->url($this->attachment_path) : null;
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url) return null;

        $url = trim($this->video_url);

        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|v/)|youtu\.be/)([A-Za-z0-9_-]{6,})~', $url, $m)) {
            return 'https://www.youtube.com/embed/'.$m[1];
        }

        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        return $url;
    }

    public function getIsEmbeddableVideoAttribute(): bool
    {
        if (! $this->video_url) return false;
        return (bool) preg_match('~(youtube\.com|youtu\.be|vimeo\.com)~', $this->video_url);
    }

    public static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'lesson';
        $slug = $base;
        $n = 2;
        while (self::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$n++;
        }
        return $slug;
    }
}
