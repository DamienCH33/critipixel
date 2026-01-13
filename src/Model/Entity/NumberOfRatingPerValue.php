<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class NumberOfRatingPerValue
{
    #[Column]
    private int $numberOfOne = 0;

    #[Column]
    private int $numberOfTwo = 0;

    #[Column]
    private int $numberOfThree = 0;

    #[Column]
    private int $numberOfFour = 0;

    #[Column]
    private int $numberOfFive = 0;

    public function clear(): void
    {
        $this->numberOfOne = 0;
        $this->numberOfTwo = 0;
        $this->numberOfThree = 0;
        $this->numberOfFour = 0;
        $this->numberOfFive = 0;
    }

    public function getNumberOfOne(): int
    {
        return $this->numberOfOne;
    }

    public function increaseOne(): void
    {
        ++$this->numberOfOne;
    }

    public function getNumberOfTwo(): int
    {
        return $this->numberOfTwo;
    }

    public function increaseTwo(): void
    {
        ++$this->numberOfTwo;
    }

    public function getNumberOfThree(): int
    {
        return $this->numberOfThree;
    }

    public function increaseThree(): void
    {
        ++$this->numberOfThree;
    }

    public function getNumberOfFour(): int
    {
        return $this->numberOfFour;
    }

    public function increaseFour(): void
    {
        ++$this->numberOfFour;
    }

    public function getNumberOfFive(): int
    {
        return $this->numberOfFive;
    }

    public function increaseFive(): void
    {
        ++$this->numberOfFive;
    }
}
