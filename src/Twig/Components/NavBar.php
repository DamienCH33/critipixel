<?php

namespace App\Twig\Components;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class NavBar
{
    public function __construct(private Security $security)
    {
    }

    public function isLoggedIn(): bool
    {
        return $this->security->isGranted('IS_AUTHENTICATED');
    }
}
