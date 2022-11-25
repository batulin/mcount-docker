<?php

namespace App\Repository;

use App\Entity\Place;
use App\Entity\Rent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeImmutable;

/**
 * @extends ServiceEntityRepository<Rent>
 *
 * @method Rent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rent[]    findAll()
 * @method Rent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rent::class);
    }

    public function save(Rent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Rent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getRentsByMonth(DateTimeImmutable $firstDay, DateTimeImmutable $lastDay):array
    {
        $query = $this->_em->createQuery('SELECT r FROM App\Entity\Rent r WHERE (r.beginDate >= :firstDay AND r.beginDate <= :lastDay) OR (r.endDate >= :firstDay AND r.endDate <= :lastDay) OR (r.beginDate <= :firstDay AND r.endDate >= :lastDay)');
        $query->setParameter('firstDay', $firstDay);
        $query->setParameter('lastDay', $lastDay);
        $rents = $query->getResult();

        return $rents;
    }

    public function isBusy(array $places_ids, DateTimeImmutable $beginDate, DateTimeImmutable $endDate):bool
    {
        $query = $this->_em->createQuery('SELECT r FROM App\Entity\Rent r WHERE :places_ids MEMBER OF r.places AND ((:beginDate >= r.beginDate AND :beginDate <= r.endDate) OR (:endDate >= r.beginDate AND :endDate <= r.endDate) OR (:beginDate <= r.beginDate AND :endDate >= r.endDate))');
        $query->setParameter('places_ids', $places_ids);
        $query->setParameter('beginDate', $beginDate);
        $query->setParameter('endDate', $endDate);
        $rents = $query->getResult();

        if(!empty($rents)) {
            return true;
        } else {
            return false;
        }
    }

}
