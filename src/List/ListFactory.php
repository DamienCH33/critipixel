<?php

declare(strict_types=1);

namespace App\List;

use App\Doctrine\Repository\VideoGameRepository;
use App\List\VideoGameList\Pagination;
use App\List\VideoGameList\VideoGamesList;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class ListFactory
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private FormFactoryInterface $formFactory,
        private VideoGameRepository $videoGameRepository,
    ) {
    }

    public function createVideoGamesList(Pagination $pagination): VideoGamesList
    {
        return new VideoGamesList(
            $this->urlGenerator,
            $this->formFactory,
            $this->videoGameRepository,
            $pagination
        );
    }
}
