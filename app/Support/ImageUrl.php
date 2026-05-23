<?php

namespace App\Support;

final class ImageUrl
{
    private const CARD_WIDTH = 640;

    private const CARD_HEIGHT = 480;

    private const HERO_WIDTH = 900;

    private const HERO_HEIGHT = 520;

    /**
     * URL для карточек каталога и превью.
     */
    public static function forCard(string $url): string
    {
        return self::optimizeRemote($url, self::CARD_WIDTH, self::CARD_HEIGHT);
    }

    /**
     * URL для слайдера на главной.
     */
    public static function forHero(string $url): string
    {
        return self::optimizeRemote($url, self::HERO_WIDTH, self::HERO_HEIGHT);
    }

    /**
     * URL для страницы товара (крупнее карточки).
     */
    public static function forDetail(string $url): string
    {
        return self::optimizeRemote($url, 900, 675);
    }

    private static function optimizeRemote(string $url, int $width, int $height): string
    {
        if ($url === '' || ! preg_match('#^https?://#i', $url)) {
            return $url;
        }

        if (str_contains($url, 'images.unsplash.com')) {
            $parts = parse_url($url);
            parse_str($parts['query'] ?? '', $query);
            $query['auto'] = 'format';
            $query['fit'] = 'crop';
            $query['w'] = (string) $width;
            $query['h'] = (string) $height;
            $query['q'] = '80';
            $query['fm'] = 'webp';

            return ($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? '').($parts['path'] ?? '').'?'.http_build_query($query);
        }

        if (preg_match('#^https?://picsum\.photos/seed/([^/]+)/(\d+)/(\d+)#i', $url, $m)) {
            return sprintf('https://picsum.photos/seed/%s/%d/%d', $m[1], $width, $height);
        }

        return $url;
    }
}
