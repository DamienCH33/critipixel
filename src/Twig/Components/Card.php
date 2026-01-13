<?php

namespace App\Twig\Components;

use App\Model\Entity\VideoGame;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Card
{
    public VideoGame $video_game;
}
