<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'price_cents',
        'stock',
        'image_path',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function getPriceAttribute(): float
    {
        return $this->price_cents / 100;
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$'.number_format($this->price / 1, 2);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image_path
            ? Storage::disk('public')->url($this->image_path)
            : 'https://placehold.co/600x400/e0e7ff/6366f1?text='.urlencode(Str::limit($this->name, 20));
    }

    public static function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $n = 2;
        while (self::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$n++;
        }
        return $slug;
    }
}
