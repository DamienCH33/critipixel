<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $existing = (new User())
            ->setEmail('existing@email.com')
            ->setUsername('existingUser')
            ->setPlainPassword('password');

        $users = array_fill_callback(0, 10, fn (int $index): User => (new User())
            ->setEmail(sprintf('user+%d@email.com', $index))
            ->setUsername(sprintf('user+%d', $index))
            ->setPlainPassword('password')
        );

        array_walk($users, [$manager, 'persist']);

        $manager->flush();
    }
}
