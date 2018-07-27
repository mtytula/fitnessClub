<?php

namespace App\Model;

use \App\Entity\Activity as ActivityEntity;

class Activity
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $slots;

    /**
     * @param ActivityEntity $activity
     * @return Activity
     */
    public function fromActivity(ActivityEntity $activity): Activity
    {
        $activityRequest = new Activity();
        $activityRequest->name = $activity->getName();
        $activityRequest->description = $activity->getDescription();
        $activityRequest->slots  = $activity->getSlots();

        return $activityRequest;
    }
}
