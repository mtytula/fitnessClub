<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 * @ORM\Table(name="schedules")
 */
class Schedule
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
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $endDate;

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
     * @var Room
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="schedules")
     */
    private $room;

    /**
     * @var Coach
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Coach", inversedBy="schedules")
     */
    private $coach;

    /**
     * @var Activity
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="schedules")
     */
    private $activity;

    /**
     * @var Participant[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Participant", inversedBy="schedules")
     */
    private $participants;


    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @param \DateTimeInterface $startDate
     * @return Schedule
     */
    public function setStartDate(\DateTimeInterface $startDate): Schedule
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @param \DateTimeInterface $endDate
     * @return Schedule
     */
    public function setEndDate(\DateTimeInterface $endDate): Schedule
    {
        $this->endDate = $endDate;

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
     * @return Schedule
     */
    public function setCreatedDate(\DateTime $createdDate): Schedule
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
     * @return Schedule
     */
    public function setUpdatedDate(\DateTime $updatedDate): Schedule
    {
        $this->updatedDate = $updatedDate;
        return $this;
    }

    /**
     * @return Room
     */
    public function getRoom(): Room
    {
        return $this->room;
    }

    /**
     * @param Room $room
     * @return Schedule
     */
    public function setRoom(Room $room): Schedule
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @param Room $room
     * @return Schedule|null
     */
    public function removeRoom(Room $room): ?Schedule
    {
        if ($this->room->contains($room)) {
            $this->room->removeElement($room);

            if ($room->getSchedules() === $this) {
                $room->addSchedule(null);
            }
        }

        return $this;
    }

    /**
     * @return Coach
     */
    public function getCoach(): Coach
    {
        return $this->coach;
    }

    /**
     * @param Coach $coach
     * @return Schedule
     */
    public function setCoach(Coach $coach): Schedule
    {
        $this->coach = $coach;

        return $this;
    }

    /**
     * @return Activity
     */
    public function getActivity(): Activity
    {
        return $this->activity;
    }

    /**
     * @param Activity $activity
     * @return Schedule
     */
    public function setActivity(Activity $activity): Schedule
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * @param Participant $participant
     * @return Schedule
     */
    public function addParticipant(Participant $participant): Schedule
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addToSchedule($this);
        }

        return $this;
    }

    /**
     * @param Participant $participant
     * @return Schedule
     */
    public function removeParticipant(Participant $participant): Schedule
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            $participant->removeFromSchedule($this);
        }

        return $this;
    }
}
