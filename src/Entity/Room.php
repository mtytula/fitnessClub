<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 * @ORM\Table(name="rooms")
 */
class Room
{
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $capacity;

    /**
     * @var \DateTime $createdDate
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var \DateTime $updatedDate
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_date", type="datetime")
     */
    private $updatedDate;

    /**
     * @var Schedule[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="room")
     */
    private $schedules;

    public function __construct()
    {
        $this->schedules = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Room
     */
    public function setName(string $name): Room
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime $createdDate
     * @return Room
     */
    public function setCreatedDate(\DateTime $createdDate): Room
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate(): \DateTime
    {
        return $this->updatedDate;
    }

    /**
     * @param \DateTime $updatedDate
     * @return Room
     */
    public function setUpdatedDate(\DateTime $updatedDate): Room
    {
        $this->updatedDate = $updatedDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     * @return Room
     */
    public function setCapacity(int $capacity): Room
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return Schedule[]
     */
    public function getSchedules(): Schedule
    {
        return $this->schedules;
    }

    /**
     * @param Schedule $schedule
     * @return Room
     */
    public function addSchedule(Schedule $schedule): Room
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setRoom($this);
        }

        return $this;
    }

    /**
     * @param Schedule $schedule
     * @return Room
     */
    public function removeSchedule(Schedule $schedule): Room
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);
            $schedule->removeRoom($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
