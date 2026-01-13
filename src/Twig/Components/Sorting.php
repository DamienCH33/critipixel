<?php

namespace App\Twig\Components;

use App\List\VideoGameList\VideoGamesList;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Sorting
{
    public VideoGamesList $list;
}
