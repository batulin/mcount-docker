<?php

namespace App\Model;

use DateTimeImmutable;
class Day
{
    private ?int $rentId = null;
    private ?DateTimeImmutable $date = null;

    public function __construct(private string $dayNumber)
    {
    }

    public function getDayNumber(): string
    {
        return $this->dayNumber;
    }

    public function getRentId(): ?int
    {
        return $this->rentId;
    }

    public function setRentId(int $rentId): self
    {
        $this->rentId = $rentId;

        return $this;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }
}