<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="coaches")
 */
class Coach
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
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="coach")
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
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Coach
     */
    public function setFirstName(string $firstName): Coach
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Coach
     */
    public function setLastName(string $lastName): Coach
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @param null|string $picture
     * @return Coach
     */
    public function setPicture(?string $picture): Coach
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return Coach
     */
    public function setDescription(?string $description): Coach
    {
        $this->description = $description;

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
     * @return Coach
     */
    public function setCreatedDate(\DateTime $createdDate): Coach
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
     * @return Coach
     */
    public function setUpdatedDate(\DateTime $updatedDate): Coach
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
     * @return Coach
     */
    public function addSchedule(Schedule $schedule): Coach
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules[] = $schedule;
            $schedule->setCoach($this);
        }

        return $this;
    }

    /**
     * @param Schedule $schedule
     * @return Coach
     */
    public function removeSchedule(Schedule $schedule): Coach
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);

            if ($schedule->getCoach() === $this) {
                $schedule->setCoach(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->firstName . " " .  $this->lastName;
    }
}
