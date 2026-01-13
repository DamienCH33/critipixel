<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\Tests\Functional\FunctionalTestCase;

final class FilterTest extends FunctionalTestCase
{
    /**
     * Fournit les cas de test pour le filtrage.
     *
     * @return iterable<string, array{
     *     query: array<string, mixed>,
     *     expectedCount: int,
     *     expectedVideoGames: string[]
     * }>
     */
    public static function provideTagFilteringCases(): iterable
    {
        yield 'Aucun tag spécifié' => [
            'query' => ['filter' => ['tags' => []]],
            'expectedCount' => 10,
            'expectedVideoGames' => [
                'Jeu vidéo 0',
                'Jeu vidéo 1',
                'Jeu vidéo 2',
                'Jeu vidéo 3',
                'Jeu vidéo 4',
                'Jeu vidéo 5',
                'Jeu vidéo 6',
                'Jeu vidéo 7',
                'Jeu vidéo 8',
                'Jeu vidéo 9',
            ],
        ];

        yield 'Un seul tag' => [
            'query' => ['filter' => ['tags' => ['1']]],
            'expectedCount' => 10,
            'expectedVideoGames' => [
                'Jeu vidéo 0',
                'Jeu vidéo 21',
                'Jeu vidéo 22',
                'Jeu vidéo 23',
                'Jeu vidéo 24',
                'Jeu vidéo 25',
                'Jeu vidéo 46',
                'Jeu vidéo 47',
                'Jeu vidéo 48',
                'Jeu vidéo 49',
            ],
        ];

        yield 'Plusieurs tags (1 et 2)' => [
            'query' => ['filter' => ['tags' => ['1', '2']]],
            'expectedCount' => 8,
            'expectedVideoGames' => [
                'Jeu vidéo 0',
                'Jeu vidéo 22',
                'Jeu vidéo 23',
                'Jeu vidéo 24',
                'Jeu vidéo 25',
                'Jeu vidéo 47',
                'Jeu vidéo 48',
                'Jeu vidéo 49',
            ],
        ];

        yield 'Tags multiples (1,2,3,4)' => [
            'query' => ['filter' => ['tags' => ['1', '2', '3', '4']]],
            'expectedCount' => 4,
            'expectedVideoGames' => [
                'Jeu vidéo 0',
                'Jeu vidéo 24',
                'Jeu vidéo 25',
                'Jeu vidéo 49',
            ],
        ];

        yield 'Tag inexistant' => [
            'query' => ['filter' => ['tags' => ['999']]],
            'expectedCount' => 10,
            'expectedVideoGames' => [],
        ];
    }

    /**
     * @dataProvider provideTagFilteringCases
     *
     * @param array<string, mixed> $query
     * @param string[]             $expectedVideoGames
     */
    public function testShouldFilterVideoGamesByTags(array $query, int $expectedCount, array $expectedVideoGames): void
    {
        $this->get('/', $query);
        self::assertResponseIsSuccessful();

        // Vérifie le nombre de cartes de jeux affichées
        self::assertSelectorCount($expectedCount, 'article.game-card');

        // Vérifie que les bons jeux apparaissent
        foreach (array_values($expectedVideoGames) as $index => $expectedVideoGame) {
            self::assertSelectorTextSame(
                sprintf('article.game-card:nth-child(%d) h2.game-card-title a', $index + 1),
                $expectedVideoGame
            );
        }

        // Cas où aucun jeu n'est trouvé
        if (0 === $expectedCount) {
            self::assertSelectorTextSame(
                'div.list-info',
                'Aucun jeu vidéo ne correspond à votre recherche.'
            );
        }
    }
}
