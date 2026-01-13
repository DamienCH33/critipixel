<?php

declare(strict_types=1);

namespace App\Rating;

use App\Model\Entity\Review;
use App\Model\Entity\VideoGame;

final readonly class RatingHandler implements CalculateAverageRating, CountRatingsPerValue
{
    public function calculateAverage(VideoGame $videoGame): void
    {
        if (0 === count($videoGame->getReviews())) {
            $videoGame->setAverageRating(null);

            return;
        }

        $ratingsSum = array_sum(
            array_map(
                static fn (Review $review): int => $review->getRating(),
                $videoGame->getReviews()->toArray()
            )
        );

        $videoGame->setAverageRating((int) ceil($ratingsSum / count($videoGame->getReviews())));
    }

    public function countRatingsPerValue(VideoGame $videoGame): void
    {
        $videoGame->getNumberOfRatingsPerValue()->clear();

        if (0 === count($videoGame->getReviews())) {
            return;
        }

        foreach ($videoGame->getReviews() as $review) {
            match ($review->getRating()) {
                1 => $videoGame->getNumberOfRatingsPerValue()->increaseOne(),
                2 => $videoGame->getNumberOfRatingsPerValue()->increaseTwo(),
                3 => $videoGame->getNumberOfRatingsPerValue()->increaseThree(),
                4 => $videoGame->getNumberOfRatingsPerValue()->increaseFour(),
                default => $videoGame->getNumberOfRatingsPerValue()->increaseFive(),
            };
        }
    }
}
