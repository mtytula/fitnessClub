<?php

namespace App\Facade;

use App\Entity\Activity;
use App\Entity\Coach;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Schedule;

class ScheduleFacade
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ScheduleFacade constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Coach $coach
     * @param Room $room
     * @param Activity $activity
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @return Schedule
     */
    public function createSchedule(
        Coach $coach,
        Room $room,
        Activity $activity,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate
    ): Schedule {
        $schedule = new Schedule();
        $schedule->setStartDate($startDate);
        $schedule->setEndDate($endDate);
        $schedule->setActivity($activity);
        $schedule->setCoach($coach);
        $schedule->setRoom($room);

        $this->entityManager->persist($schedule);
        $this->entityManager->flush();

        return $schedule;
    }

    /**
     * @param Schedule $schedule
     * @param Coach $coach
     * @param Room $room
     * @param Activity $activity
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @return Schedule
     */
    public function updateSchedule(
        Schedule $schedule,
        Coach $coach,
        Room $room,
        Activity $activity,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate
    ): Schedule {
        $schedule->setStartDate($startDate);
        $schedule->setEndDate($endDate);
        $schedule->setActivity($activity);
        $schedule->setCoach($coach);
        $schedule->setRoom($room);

        $this->entityManager->persist($schedule);
        $this->entityManager->flush();

        return $schedule;
    }

    /**
     * @param Schedule $schedule
     */
    public function deleteSchedule(Schedule $schedule): void
    {
        $this->entityManager->remove($schedule);
        $this->entityManager->flush();
    }
}
