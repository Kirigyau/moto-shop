<?php

namespace App\Support;

final class StoredImage
{
    /**
     * Путь из админки (загрузка на диск public).
     */
    public static function isUploadedPath(string $path): bool
    {
        $path = ltrim(trim($path), '/');

        return str_starts_with($path, 'products/')
            || str_starts_with($path, 'hero/')
            || str_starts_with($path, 'storage/products/')
            || str_starts_with($path, 'storage/hero/');
    }

    /**
     * Демо-URL из сида (можно обновлять при деплое).
     */
    public static function isRemoteDemoUrl(string $path): bool
    {
        $path = trim($path);

        return $path === ''
            || $path === '_'
            || str_contains($path, 'images.unsplash.com')
            || str_contains($path, 'picsum.photos');
    }
}
