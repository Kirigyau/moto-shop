<?php

namespace App\Support;

final class Phone
{
    public static function normalize(?string $phone): ?string
    {
        if ($phone === null || $phone === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits === null || $digits === '') {
            return null;
        }

        if (strlen($digits) === 10 && str_starts_with($digits, '9')) {
            $digits = '7'.$digits;
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '8')) {
            $digits = '7'.substr($digits, 1);
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '7')) {
            return $digits;
        }

        return null;
    }
}
