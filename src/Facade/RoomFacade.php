<?php

namespace App\Facade;

use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;

class RoomFacade
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * RoomFacade constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $name
     * @param int $capacity
     * @return Room
     */
    public function createRoom(string $name, int $capacity): Room
    {
        $room = new Room();

        $room
            ->setName($name)
            ->setCapacity($capacity);

        $this->entityManager->persist($room);
        $this->entityManager->flush();

        return $room;
    }

    /**
     * @param Room $room
     * @param string $name
     * @param int $capacity
     * @return Room
     */
    public function updateRoom(Room $room, string $name, int $capacity): Room
    {
        $room
            ->setName($name)
            ->setCapacity($capacity);

        $this->entityManager->persist($room);
        $this->entityManager->flush();

        return $room;
    }

    /**
     * @param Room $room
     */
    public function deleteRoom(Room $room): void
    {
        $this->entityManager->remove($room);
        $this->entityManager->flush();
    }
}
