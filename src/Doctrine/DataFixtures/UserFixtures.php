<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = \array_fill_callback(0, 10, fn (int $index): User => (new User())
            ->setEmail(sprintf('user+%d@email.com', $index))
            ->setPlainPassword('password')
            ->setUsername(sprintf('user+%d', $index))
        );

        array_walk($users, [$manager, 'persist']);

        $manager->flush();
    }
}
