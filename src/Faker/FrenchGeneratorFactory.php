<?php

declare(strict_types=1);

namespace App\Faker;

use Faker\Factory;
use Faker\Generator;

final readonly class FrenchGeneratorFactory
{
    public static function create(): Generator
    {
        return Factory::create('fr_FR');
    }
}
