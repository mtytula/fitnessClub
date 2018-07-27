<?php

namespace App\Facade;

use App\Entity\Coach;
use Doctrine\ORM\EntityManagerInterface;

class CoachFacade
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * CoachFacade constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $picture
     * @param string $description
     * @return Coach
     */
    public function createCoach(string $firstName, string $lastName, string $picture, string $description): Coach
    {
        $coach = new Coach();
        $coach
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPicture($picture)
            ->setDescription($description);

        $this->entityManager->persist($coach);
        $this->entityManager->flush();

        return $coach;
    }

    /**
     * @param Coach $coach
     * @param string $firstName
     * @param string $lastName
     * @param string $picture
     * @param string $description
     * @return Coach Coach
     */
    public function updateCoach(
        Coach $coach,
        string $firstName,
        string $lastName,
        string $picture,
        string $description
    ): Coach {
        $coach
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPicture($picture)
            ->setDescription($description);

        $this->entityManager->persist($coach);
        $this->entityManager->flush();

        return $coach;
    }
}
