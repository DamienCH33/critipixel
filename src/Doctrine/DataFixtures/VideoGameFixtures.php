<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Review;
use App\Model\Entity\Tag;
use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use App\Rating\CalculateAverageRating;
use App\Rating\CountRatingsPerValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

final class VideoGameFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly Generator $faker,
        private readonly CalculateAverageRating $calculateAverageRating,
        private readonly CountRatingsPerValue $countRatingsPerValue,
    ) {}

    public function load(ObjectManager $manager): void
    {
        /** @var Tag[] $tags */
        $tags = $manager->getRepository(Tag::class)->findAll();
        /** @var User[][] $users */
        $users = array_chunk($manager->getRepository(User::class)->findAll(), 5);

        $fakeText = $this->faker->paragraphs(5, true);

        /** @var VideoGame[] $videoGames */
        $videoGames = array_map(
            fn(int $index): VideoGame => (new VideoGame())
                ->setTitle(sprintf('Jeu vidéo %d', $index))
                ->setDescription($fakeText)
                ->setReleaseDate((new \DateTimeImmutable())->sub(new \DateInterval(sprintf('P%dD', $index))))
                ->setTest($fakeText)
                ->setRating(($index % 5) + 1)
                ->setImageName(sprintf('video_game_%d.png', $index))
                ->setImageSize(2_098_872),
            range(0, 49)
        );

        foreach ($videoGames as $index => $videoGame) {
            // Tags
            for ($tagIndex = 0; $tagIndex < 5; ++$tagIndex) {
                $videoGame->getTags()->add($tags[($index + $tagIndex) % count($tags)]);
            }

            $manager->persist($videoGame);
        }

        $manager->flush();

        foreach ($videoGames as $index => $videoGame) {
            $groupIndex = $index % count($users);
            $filteredUsers = $users[$groupIndex] ?? [];

            foreach ($filteredUsers as $user) {
                $review = $this->generateReview($user, $videoGame);
                $videoGame->getReviews()->add($review);
                $manager->persist($review);

                $this->calculateAverageRating->calculateAverage($videoGame);
                $this->countRatingsPerValue->countRatingsPerValue($videoGame);
            }
        }

        $manager->flush();
    }

    private function generateReview(User $user, VideoGame $game): Review
    {
        $rating = $this->faker->numberBetween(1, 5);
        $comment = $this->faker->paragraphs(1, true);

        $review = (new Review())
            ->setUser($user)
            ->setVideoGame($game)
            ->setRating($rating)
            ->setComment($comment);

        return $review;
    }

    public function getDependencies(): array
    {
        return [TagFixtures::class, UserFixtures::class];
    }
}
