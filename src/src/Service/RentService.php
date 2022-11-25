<?php

namespace App\Service;

use App\Entity\Rent;
use App\Exception\UnCorrectDateForRentException;
use App\Model\Day;
use App\Model\CalendarModel;
use App\Model\PlaceModel;
use App\Repository\PlaceRepository;
use App\Repository\RentRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use App\Entity\Place;
use DateInterval;
use DatePeriod;

class RentService
{
    public function __construct(private EntityManagerInterface $em,
                                private RentRepository $rents,
                                private PlaceRepository $places)
    {
    }

    public function allRents(): array
    {
        return $this->rents->findAll();
    }

    public function placeById(string $place_id):Place
    {
       return $place = $this->places->find($place_id);
    }

    public function createRent(Rent $rent):array
    {
        $beginDate = $rent->getBeginDate();
        $endDate = $rent->getEndDate();
        if($beginDate >= $endDate) {
            throw new UnCorrectDateForRentException();
        }
        $places = $rent->getPlaces();
        $place = $places[0];
        return $this->rents->isBusy($place);
    }

    public function placesWithRents(array $dateInfo):CalendarModel
    {
        $places = $this->places->findAll([], ['number'=>'ASC']);
        $rents = $this->rents->getRentsByMonth($dateInfo['firstDay'], $dateInfo['lastDay']);
        // все, что ниже вставить в цикл мест
        // перед циклом с датами сделать массив заказов (пустой) и сделать ципл проверки заказов
        // по месту и поместить в массив, а в цикле с датами вместо заказов всех вставить заказы места
        $items = [];
        foreach ($places as $place) {

            $placeModel = new PlaceModel($place->getId(), $place->getNumber());
            $placeRents = [];
            foreach ($rents as $rent) {
                foreach ($rent->getPlaces() as $rentPlace) {
                    if($rentPlace->getId() === $place->getId()){
                        array_push($placeRents, $rent);
                    }
                }
            }

            foreach ($dateInfo['period'] as $calendarDay) {
                $day = new Day($calendarDay->format('d'));

                foreach ($placeRents as $placeRent) {
                    if ($placeRent->getBeginDate() <= $calendarDay && $placeRent->getEndDate() >= $calendarDay) {
                        $day->setRentId($placeRent->getId());
                        $day->setDate(null);
                        break;
                    } else {
                        if(null === $day->getRentId()) {
                            $day->setDate($calendarDay);
                        } else {
                            break;
                        }
                    }
                }
                $placeModel->addDay($day);

            }
            array_push($items, $placeModel);
        }

        return new CalendarModel($items);
    }



    public function getDateInfo($month): array
    {
        $dateString = $month . '-01 00:00:00';
        $firstDay = new DateTimeImmutable($dateString);
        $lastDay = new DateTimeImmutable(sprintf('last day of %s', $firstDay->format('Y-m')));
        $interval = new DateInterval('P1D');
        $period = iterator_to_array(new DatePeriod($firstDay, $interval, $lastDay->modify('+1 day')));
        $nextMonth = $firstDay->add(new DateInterval('P1M'));
        $prevMonth = $firstDay->sub(new DateInterval('P1M'));

        return $monthInfo = [
            'period' => $period,
            'firstDay' => $firstDay,
            'lastDay' => $lastDay,
            'nextMonth' => $nextMonth,
            'prevMonth' => $prevMonth
        ];
    }
}