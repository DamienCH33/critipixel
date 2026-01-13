<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Model\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

abstract class FunctionalTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    /** Retourne l'EntityManager pour accéder à la BDD */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->service(EntityManagerInterface::class);
    }

    /**
     * Récupère un service depuis le conteneur Symfony.
     *
     * @template T
     *
     * @param class-string<T> $id
     *
     * @return T
     */
    protected function service(string $id): object
    {
        return static::getContainer()->get($id);
    }

    /** Effectue une requête GET */
    protected function get(string $uri, array $parameters = []): Crawler
    {
        return $this->client->request('GET', $uri, $parameters);
    }

    /** Effectue une requête POST */
    protected function post(string $uri, array $parameters = []): Crawler
    {
        return $this->client->request('POST', $uri, $parameters);
    }

    /** Soumet un formulaire en ciblant le bouton par son label */
    protected function submit(string $buttonLabel, array $values = []): void
    {
        $crawler = $this->client->getCrawler();
        $form = $crawler->selectButton($buttonLabel)->form($values);
        $this->client->submit($form);
    }

    /** Connecte un utilisateur existant (par email) */
    protected function login(string $email = 'user+0@email.com'): void
    {
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            throw new \RuntimeException(sprintf('Aucun utilisateur trouvé avec l’email "%s".', $email));
        }

        $this->client->loginUser($user);
    }

    /** Récupère l'utilisateur actuellement connecté */
    protected function getLoggedUser(): ?User
    {
        $tokenStorage = $this->service('security.token_storage');
        $token = $tokenStorage->getToken();

        if (!$token || !is_object($token->getUser())) {
            return null;
        }

        return $token->getUser();
    }

    /** Suit la redirection après une requête */
    protected function followRedirect(): void
    {
        $this->client->followRedirect();
    }
}
