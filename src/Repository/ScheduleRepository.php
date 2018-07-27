<?php

namespace App\Repository;

use App\Entity\Schedule;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ScheduleRepository extends ServiceEntityRepository
{
    /**
     * ScheduleRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

    /**
     * @param DateTime $firstDate
     * @param DateTime $lastDate
     * @return array
     */
    public function findByDate(DateTime $firstDate, DateTime $lastDate): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('q')
            ->from('App:Schedule', 'q')
            ->where('q.createdDate BETWEEN :firstDate AND :lastDate')
            ->setParameters([
                'firstDate' => $firstDate,
                'lastDate' => $lastDate
            ]);

        return $schedules = $qb->getQuery()->getResult();
    }

    /**
     * @param DateTime $day
     * @return array
     */
    public function findAllByDay(DateTime $day): array
    {
        $endOfDay = clone $day;
        $day->modify('today');
        $endOfDay->modify('tomorrow')->modify('1 second ago');

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('q')
            ->from('App:Schedule', 'q')
            ->where('q.startDate BETWEEN :beginDate AND :endDate')
            ->setParameters([
                'beginDate' => $day,
                'endDate' => $endOfDay
            ]);

        return $schedules = $qb->getQuery()->getResult();
    }
}
