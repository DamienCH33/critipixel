<?php

declare(strict_types=1);

namespace App\Model\ValueObject;

final readonly class Page
{
    public function __construct(
        public int $page,
        public bool $active,
        public string $label,
        public string $url,
    ) {
    }
}
