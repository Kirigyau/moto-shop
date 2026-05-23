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
