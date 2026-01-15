<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ReviewType;
use App\List\ListFactory;
use App\List\VideoGameList\Pagination;
use App\Model\Entity\Review;
use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'video_games_')]
final class VideoGameController extends AbstractController
{
    #[Route(name: 'list', methods: [Request::METHOD_GET])]
    public function list(
        #[ValueResolver('pagination')]
        Pagination $pagination,
        Request $request,
        ListFactory $listFactory,
    ): Response {
        $videoGamesList = $listFactory->createVideoGamesList($pagination)->handleRequest($request);

        return $this->render('views/video_games/list.html.twig', ['list' => $videoGamesList]);
    }

    #[Route('{slug}', name: 'show', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function show(VideoGame $videoGame, EntityManagerInterface $entityManager, Request $request): Response
    {
        $review = new Review();
        $review->setVideoGame($videoGame);

        /** @var User|null $user */
        $user = $this->getUser();
        if ($user) {
            $review->setUser($user);
        }

        $form = $this->createForm(ReviewType::class, $review)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user) {
                return $this->redirectToRoute('auth_login');
            }

            $this->denyAccessUnlessGranted('review', $videoGame);

            $existingReview = $entityManager->getRepository(Review::class)->findOneBy([
                'videoGame' => $videoGame,
                'user' => $user,
            ]);

            if ($existingReview) {
                return $this->redirectToRoute('video_games_show', ['slug' => $videoGame->getSlug()]);
            }

            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('video_games_show', ['slug' => $videoGame->getSlug()]);
        }

        return $this->render('views/video_games/show.html.twig', [
            'video_game' => $videoGame,
            'form' => $form,
        ]);
    }
}
