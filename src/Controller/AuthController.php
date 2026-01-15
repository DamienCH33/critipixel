<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\RegisterType;
use App\Model\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/auth', name: 'auth_')]
final class AuthController extends AbstractController
{
    #[Route('/login', name: 'login', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('views/auth/login.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

   #[Route('/register', name: 'register')]
public function register(
    Request $request,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
): Response {
    $user = new User();
    $form = $this->createForm(RegisterType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if ($form->isValid()) {
            // Hash du mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->get('first')->getData()
            );
            $user->setPassword($hashedPassword);

            // Persistance en base
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirection seulement si tout est ok
            return $this->redirectToRoute('auth_login');
        }

        // Ici, Symfony va automatiquement gérer les erreurs de validation
        // (ex : UniqueEntity sur username ou email)
        // et les transmettre à la vue
    }

    return $this->render('views/auth/register.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
