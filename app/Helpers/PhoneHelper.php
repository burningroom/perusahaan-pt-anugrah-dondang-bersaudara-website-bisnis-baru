<?php

namespace App\Helpers;

class PhoneHelper
{
    public static function formatNumber(?string $phone): ?string
    {
        if (empty($phone)) return null;
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '62')) {
            $phone = substr($phone, 2);
        } elseif (str_starts_with($phone, '08')) {
            $phone = substr($phone, 1);
        }

        return $phone;
    }
}
