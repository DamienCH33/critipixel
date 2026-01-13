<?php

declare(strict_types=1);

namespace App\Model\Trait;

trait EnumTrait
{
    public static function tryFromName(string $name): ?static
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }
}
