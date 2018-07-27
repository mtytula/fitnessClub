<?php

namespace App\Model;

use App\Entity\Room as EntityRoom;

class Room
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $capacity;

    /**
     * @param EntityRoom $room
     * @return Room
     */
    public function fromRoom(EntityRoom $room): Room
    {
        $roomModel = new Room();
        $roomModel->name = $room->getName();
        $roomModel->capacity = $room->getCapacity();

        return $roomModel;
    }
}
