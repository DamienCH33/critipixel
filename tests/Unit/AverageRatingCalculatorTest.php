<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Model\Entity\Review;
use App\Model\Entity\VideoGame;
use App\Rating\RatingHandler;
use PHPUnit\Framework\TestCase;

final class AverageRatingCalculatorTest extends TestCase
{
    private RatingHandler $ratingHandler;

    protected function setUp(): void
    {
        $this->ratingHandler = new RatingHandler();
    }

    /**
     * @dataProvider provideVideoGames
     */
    public function testCalculateAverage(VideoGame $videoGame, ?int $expected): void
    {
        $this->ratingHandler->calculateAverage($videoGame);
        self::assertSame($expected, $videoGame->getAverageRating());
    }

    public static function provideVideoGames(): iterable
    {
        yield 'no review' => [new VideoGame(), null];
        yield 'single review' => [self::createVideoGame(5), 5];
        yield 'multiple reviews' => [self::createVideoGame(1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 5), 4];
    }

    private static function createVideoGame(int ...$ratings): VideoGame
    {
        $game = new VideoGame();

        foreach ($ratings as $rating) {
            $game->getReviews()->add((new Review())->setRating($rating));
        }

        return $game;
    }
}
