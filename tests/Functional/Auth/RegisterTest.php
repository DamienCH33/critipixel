<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Model\Entity\User;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $em = $this->getEntityManager();

        $user = new User();
        $user->setUsername('existingUser');
        $user->setEmail('existing@email.com');
        $user->setPlainPassword('SuperPassword123!');
        $user->setPassword('dummy');

        $em->persist($user);
        $em->flush();
    }
    public function testThatRegistrationShouldSucceeded(): void
    {
        $this->get('/auth/register');

        $unique = uniqid('user_', true);

        $formData = self::getFormData([
            'register[username]' => $unique,
            'register[email]' => $unique . '@email.com',
        ]);

        $this->client->submitForm('S\'inscrire', $formData);



        $user = $this->getEntityManager()->getRepository(User::class)
            ->findOneBy(['email' => $unique . '@email.com']);

        $userPasswordHasher = $this->service(UserPasswordHasherInterface::class);

        self::assertNotNull($user);
        self::assertSame($unique, $user->getUsername());
        self::assertSame($unique . '@email.com', $user->getEmail());
        self::assertTrue($userPasswordHasher->isPasswordValid($user, 'SuperPassword123!'));
    }

    /**
     * @dataProvider provideInvalidFormData
     * @param array<string, string> $formData
     */
    public function testThatRegistrationShouldFailed(array $formData): void
    {
        $this->get('/auth/register');

        $this->client->submitForm('S\'inscrire', $formData);

        $response = $this->client->getResponse();

        if ($response->isRedirection()) {
            self::fail('Expected form errors but got a redirect to ' . $response->headers->get('Location'));
        }

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSelectorExists('.form-error-message');
    }

    /**
     * @return iterable<string, array{array<string, string>}>
     */
    public static function provideInvalidFormData(): iterable
    {
        yield 'empty username' => [self::getFormData(['register[username]' => ''])];
        yield 'non unique username' => [self::getFormData(['register[username]' => 'existingUser'])];
        yield 'too long username' => [self::getFormData(['register[username]' => str_repeat('a', 51)])];
        yield 'empty email' => [self::getFormData(['register[email]' => ''])];
        yield 'non unique email' => [self::getFormData(['register[email]' => 'existing@email.com'])];
        yield 'invalid email' => [self::getFormData(['register[email]' => 'fail'])];
    }

    /**
     * @param array<string, string> $overrideData
     * @return array<string, string>
     */
    public static function getFormData(array $overrideData = []): array
    {
        return array_merge([
            'register[username]' => 'username',
            'register[email]' => 'user@email.com',
            'register[plainPassword][first]' => 'SuperPassword123!',
            'register[plainPassword][second]' => 'SuperPassword123!',
        ], $overrideData);
    }
}
