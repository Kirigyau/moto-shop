<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    public const MAX_SLIDES = 3;

    protected $fillable = [
        'sort_order',
        'image',
        'alt',
        'link',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    protected function imageSrc(): Attribute
    {
        return Attribute::get(function (): string {
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
        });
    }
}
