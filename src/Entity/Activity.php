<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="activities")
 */
class Activity
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
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $slots;

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
     * @var Opinion[] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Opinion", mappedBy="activity",cascade={"persist"})
     */
    private $opinions;

    /**
     * @var Schedule[] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="activity")
     */
    private $schedules;

    public function __construct()
    {
        $this->schedules = new ArrayCollection();
        $this->opinions = new ArrayCollection();
    }

    /**
     * @return int
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
     * @return Activity
     */
    public function setName(string $name): Activity
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Activity
     */
    public function setDescription(string $description): Activity
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSlots(): int
    {
        return $this->slots;
    }

    /**
     * @param int $slots
     * @return Activity
     */
    public function setSlots(int $slots): Activity
    {
        $this->slots = $slots;

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
     * @return Activity
     */
    public function setCreatedDate(\DateTime $createdDate): Activity
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
     * @return Activity
     */
    public function setUpdatedDate(\DateTime $updatedDate): Activity
    {
        $this->updatedDate = $updatedDate;
        return $this;
    }

    /**
     * @return ArrayCollection|Schedule[]
     */
    public function getSchedules(): ArrayCollection
    {
        return $this->schedules;
    }

    /**
     * @param Schedule $schedule
     * @return Activity
     */
    public function addSchedule(Schedule $schedule): Activity
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules[] = $schedule;
            $schedule->setActivity($this);
        }

        return $this;
    }

    /**
     * @param Schedule $schedule
     * @return Activity
     */
    public function removeSchedule(Schedule $schedule): Activity
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);

            if ($schedule->getActivity() === $this) {
                $schedule->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Opinion[]
     */
    public function getOpinions(): ArrayCollection
    {
        return $this->opinions;
    }

    /**
     * @param Opinion $opinion
     * @return Activity
     */
    public function addOpinion(Opinion $opinion): Activity
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions->add($opinion);
            $opinion->setActivity($this);
        }

        return $this;
    }

    /**
     * @param Opinion $opinion
     * @return Activity
     */
    public function removeOpinion(Opinion $opinion): Activity
    {
        if ($this->opinions->contains($opinion)) {
            $this->opinions->removeElement($opinion);

            if ($opinion->getActivity() === $this) {
                $opinion->setActivity(null);
            }
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
