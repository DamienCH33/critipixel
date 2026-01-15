<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Model\Entity\NumberOfRatingPerValue;
use App\Model\Entity\Review;
use App\Model\Entity\VideoGame;
use App\Rating\RatingHandler;
use PHPUnit\Framework\TestCase;

final class NoteCalculatorTest extends TestCase
{
    private RatingHandler $ratingHandler;

    protected function setUp(): void
    {
        $this->ratingHandler = new RatingHandler();
    }

    /**
     * @dataProvider provideVideoGames
     */
    public function testCountRatingsPerValue(VideoGame $videoGame, NumberOfRatingPerValue $expected): void
    {
        $this->ratingHandler->countRatingsPerValue($videoGame);
        self::assertEquals($expected, $videoGame->getNumberOfRatingsPerValue());
    }

    /**
     * @return iterable<string, array{VideoGame, NumberOfRatingPerValue}>
     */
    public static function provideVideoGames(): iterable
    {
        yield 'no review' => [new VideoGame(), new NumberOfRatingPerValue()];
        yield 'single review' => [self::createVideoGame(5), self::expected(0, 0, 0, 0, 1)];
        yield 'multiple reviews' => [
            self::createVideoGame(1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 5),
            self::expected(1, 2, 3, 4, 5),
        ];
    }

    private static function createVideoGame(int ...$ratings): VideoGame
    {
        $game = new VideoGame();

        foreach ($ratings as $rating) {
            $game->getReviews()->add((new Review())->setRating($rating));
        }

        return $game;
    }

    private static function expected(
        int $one = 0,
        int $two = 0,
        int $three = 0,
        int $four = 0,
        int $five = 0,
    ): NumberOfRatingPerValue {
        $expected = new NumberOfRatingPerValue();

        foreach (
            [
                'One' => $one,
                'Two' => $two,
                'Three' => $three,
                'Four' => $four,
                'Five' => $five,
            ] as $method => $count
        ) {
            for ($i = 0; $i < $count; ++$i) {
                $expected->{"increase$method"}();
            }
        }

        return $expected;
    }
}
