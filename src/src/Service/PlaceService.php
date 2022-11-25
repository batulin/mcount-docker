<?php

namespace App\Service;

use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Place;
use DateInterval;
use DatePeriod;

class PlaceService
{
    public function __construct(private EntityManagerInterface $em,
                                private PlaceRepository $places)
    {
    }

    public function placeById(string $place_id):Place
    {
       return $place = $this->places->find($place_id);
    }

}