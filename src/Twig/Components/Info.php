<?php

namespace App\Twig\Components;

use App\Model\ValueObject\Info as VOInfo;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Info
{
    public VOInfo $info;
}
