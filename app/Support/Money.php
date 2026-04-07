<?php

namespace App\Support;

use NumberFormatter;

class Money
{
    /**
     * Formats a numeric amount using locale + currency, with a safe fallback
     * when Intl isn't installed.
     */
    public static function format(float|int|string $amount, string $currency = 'RWF', string $locale = 'fr_RW'): string
    {
        $value = (float) $amount;

        if (class_exists(NumberFormatter::class)) {
            $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

            $formatted = $formatter->formatCurrency($value, $currency);

            if ($formatted !== false) {
                return $formatted;
            }
        }

        return $currency . ' ' . number_format($value, 2, ',', ' ');
    }
}

