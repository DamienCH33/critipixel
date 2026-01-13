<?php

namespace App\Twig\Components;

use App\List\VideoGameList\Pagination as PaginationList;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Pagination
{
    public PaginationList $pagination;
}
