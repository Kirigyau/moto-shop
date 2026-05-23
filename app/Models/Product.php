<?php

namespace App\Models;

use App\Support\ImageUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category',
        'subcategory',
        'brand',
        'engine_type',
        'title',
        'slug',
        'price',
        'old_price',
        'badge',
        'image',
        'specs',
        'description',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'specs' => 'array',
            'price' => 'integer',
            'old_price' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Рабочий URL картинки: относительные пути из админки (/storage/...) и полные ссылки.
     */
    protected function imageSrc(): Attribute
    {
        return Attribute::get(fn (): string => $this->resolveImagePath());
    }

    protected function imageCardSrc(): Attribute
    {
        return Attribute::get(fn (): string => ImageUrl::forCard($this->resolveImagePath()));
    }

    protected function imageDetailSrc(): Attribute
    {
        return Attribute::get(fn (): string => ImageUrl::forDetail($this->resolveImagePath()));
    }

    private function resolveImagePath(): string
    {
        $img = (string) ($this->attributes['image'] ?? '');
        if ($img === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $img)) {
            return $img;
        }
        if (str_starts_with($img, '/')) {
            return $img;
        }
        $trim = ltrim($img, '/');
        if (str_starts_with($trim, 'storage/')) {
            return '/'.$trim;
        }

        return '/storage/'.$trim;
    }

    /**
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
}
