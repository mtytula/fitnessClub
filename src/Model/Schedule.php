<?php

namespace App\Model;

//use App\Model\Coach;
use App\Entity\Schedule as EntitySchedule;

class Schedule
{
    /*
     * @var \DateTimeInterface
     */
    public $startDate;
    /*
     * @var \DateTimeInterface
     */
    public $endDate;

    /**
     * @var Coach
     */
    public $coach;
    /**
     * @var Activity
     */
    public $activity;
    /**
     * @var Room
     */
    public $room;

    public function fromSchedule(EntitySchedule $schedule): Schedule
    {
        $scheduleModel = new Schedule();
        $scheduleModel->startDate = $schedule->getStartDate();
        $scheduleModel->endDate = $schedule->getEndDate();
        $scheduleModel->coach = $schedule->getCoach();
        $scheduleModel->activity = $schedule->getActivity();
        $scheduleModel->room = $schedule->getRoom();

        return $scheduleModel;
    }
}
