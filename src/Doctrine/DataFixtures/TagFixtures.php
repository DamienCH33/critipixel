<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var Tag[] $tags */
        $tags = array_map(
            static fn (int $index): Tag => (new Tag())->setName(sprintf('Tag %d', $index)),
            range(0, 24)
        );

        foreach ($tags as $tag) {
            $manager->persist($tag);
        }

        $manager->flush();
    }
}
