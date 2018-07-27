<?php

namespace App\Facade;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;

class ParticipantFacade
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * ParticipantFacade constructor
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @return Participant
     */
    public function createParticipant(string $firstName, string $lastName, string $email): Participant
    {
        $participant = new Participant();
        $participant
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email);

        $this->entityManager->persist($participant);
        $this->entityManager->flush();

        return $participant;
    }

    /**
     * @param Participant $participant
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @return Participant Participant
     */
    public function updateParticipant(
        Participant $participant,
        string $firstName,
        string $lastName,
        string $email
    ): Participant {
        $participant
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email);

        $this->entityManager->persist($participant);
        $this->entityManager->flush();

        return $participant;
    }

    /**
     * @param Participant $participant
     */
    public function delete(Participant $participant): void
    {
        $this->entityManager->remove($participant);
        $this->entityManager->flush();
    }
}
