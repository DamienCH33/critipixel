<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\Model\Entity\VideoGame;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ShowTest extends FunctionalTestCase
{
    private ?VideoGame $videoGame = null;

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();
        $em = $container->get('doctrine')->getManager();

        // Récupération d'un utilisateur, mais on ne le stocke plus comme propriété
        $user = $em->getRepository('App\Model\Entity\User')->findOneBy(['username' => 'user+0']);

        $this->videoGame = $em->getRepository(VideoGame::class)->findOneBy(['title' => 'Jeu vidéo 0']);
    }

    /** Test que la page du jeu s’affiche correctement */
    public function testShouldShowVideoGame(): void
    {
        $this->get('/jeu-video-0');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', $this->videoGame->getTitle());
    }

    /** Cas nominal : ajout d'une review par un utilisateur connecté */
    public function testShouldPostReview(): void
    {
        $this->login(); 
        $this->get('/jeu-video-49');
        self::assertResponseIsSuccessful();

        $this->submit('Poster', [
            'review[rating]' => 4,
            'review[comment]' => 'Mon commentaire',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();
        self::assertSelectorTextContains('div.list-group-item:last-child h3', 'user+0');
        self::assertSelectorTextContains('div.list-group-item:last-child p', 'Mon commentaire');
        self::assertSelectorTextContains('div.list-group-item:last-child span.value', '4');
    }

    /** Test que le formulaire d’ajout de review n’apparaît pas pour un invité */
    public function testFormNotVisibleForGuest(): void
    {
        $this->get('/jeu-video-49');
        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('form[name="review"]');
    }

    /** Test qu’un invité ne peut pas poster de review */
    public function testGuestCannotPostReview(): void
    {
        $this->post('/jeu-video-49', [
            'review[rating]' => 4,
            'review[comment]' => 'Test invité',
        ]);
        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('form[name="review"]');
    }

    /** Test validation : note manquante ou commentaire trop long */
    public function testValidationErrors(): void
    {
        $this->login();
        $this->get('/jeu-video-49');

        $this->submit('Poster', [
            'review[comment]' => str_repeat('A', 281),
        ]);

        self::assertSelectorNotExists('form[name="review[comment]"]');
        self::assertSelectorCount(2, '.invalid-feedback');
    }

    /** Test qu’un utilisateur ne peut pas poster plusieurs reviews pour le même jeu */
    public function testUserCannotPostMultipleReviews(): void
    {
        $this->login();
        $this->get('/jeu-video-49');

        $this->submit('Poster', [
            'review[rating]' => 5,
            'review[comment]' => 'Première review',
        ]);

        $this->client->followRedirect();

        self::assertSelectorNotExists('form[name="review"]');
    }
}
