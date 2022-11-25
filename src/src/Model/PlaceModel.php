<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;

class PlaceModel
{
    private ArrayCollection $days;

    public function __construct(private int $id, private string $number)
    {
        $this->days = new ArrayCollection();
    }

    public function getId():int
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getDays(): ArrayCollection
    {
        return $this->days;
    }

    public function addDay(Day $day):self
    {
        $this->days->add($day);

        return $this;
    }
}