<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskLimit: int
{
    case DefaultUser = 5;
    case PremiumUser = 100;

    public static function getLimit(bool $isPremium): int
    {
        if ($isPremium) {
            return self::PremiumUser->value;
        }

        return self::DefaultUser->value;
    }
}
