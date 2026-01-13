<?php

declare(strict_types=1);

namespace App\Model\ValueObject;

use App\Model\Trait\EnumTrait;

enum Sorting: string
{
    use EnumTrait;

    case ReleaseDate = 'Date de sortie';
    case Title = 'Titre';
    case Rating = 'Note CritiPixel';
    case AverageRating = 'Note moyenne';

    public function getSql(): string
    {
        return match ($this) {
            self::ReleaseDate => 'vg.releaseDate',
            self::Title => 'vg.title',
            self::Rating => 'vg.rating',
            self::AverageRating => 'vg.averageRating',
        };
    }
}
