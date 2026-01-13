<?php

declare(strict_types=1);

namespace App\Model\ValueObject;

use App\Model\Trait\EnumTrait;

enum Direction: string
{
    use EnumTrait;

    case Ascending = 'Croissant';
    case Descending = 'Décroissant';

    public function getSql(): string
    {
        return match ($this) {
            self::Ascending => 'asc',
            self::Descending => 'desc',
        };
    }
}
