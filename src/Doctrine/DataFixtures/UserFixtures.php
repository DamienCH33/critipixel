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

        /** @var User[] $users */
        $users = array_map(
            fn(int $index): User => (new User())
                ->setEmail(sprintf('user+%d@email.com', $index))
                ->setUsername(sprintf('user+%d', $index))
                ->setPlainPassword('password'),
            range(0, 9)
        );

        foreach ($users as $user) {
            $manager->persist($user);
        }

        $manager->flush();
    }
}
