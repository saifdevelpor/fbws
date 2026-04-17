<?php

namespace App\Support;

class Phone
{
    public static function toWhatsapp(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        // 03272000339 -> 923272000339
        if (preg_match('/^0\d{10}$/', $digits)) {
            return '92' . substr($digits, 1);
        }

        return $digits;
    }
}
