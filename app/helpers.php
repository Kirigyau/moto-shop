<?php

if (! function_exists('public_asset')) {
    /**
     * Относительный URL к файлу в public/ (не зависит от APP_URL на хостинге).
     */
    function public_asset(string $path): string
    {
        return '/'.ltrim($path, '/');
    }
}

if (! function_exists('public_url')) {
    /**
     * Относительный URL маршрута (для fetch/AJAX на хостинге).
     */
    function public_url(string $path): string
    {
        return '/'.ltrim($path, '/');
    }
}
