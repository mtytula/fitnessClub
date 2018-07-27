<?php

namespace App\Facade;

use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;

class ActivityFacade
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * ActivityFacade constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $name
     * @param string $description
     * @param string $slots
     * @return Activity
     */
    public function createActivity(string $name, string $description, string $slots): Activity
    {
        $activity = new Activity();
        $activity
            ->setName($name)
            ->setDescription($description)
            ->setSlots($slots);

        $this->entityManager->persist($activity);
        $this->entityManager->flush();

        return $activity;
    }

    /**
     * @param Activity $activity
     * @param string $name
     * @param string $description
     * @param string $slots
     * @return Activity
     */
    public function updateActivity(Activity $activity, string $name, string $description, string $slots): Activity
    {
        $activity
            ->setName($name)
            ->setDescription($description)
            ->setSlots($slots);

        $this->entityManager->persist($activity);
        $this->entityManager->flush();

        return $activity;
    }
}
