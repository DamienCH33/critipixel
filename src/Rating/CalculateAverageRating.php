<?php

declare(strict_types=1);

namespace App\Rating;

use App\Model\Entity\VideoGame;

interface CalculateAverageRating
{
    public function calculateAverage(VideoGame $videoGame): void;
}
