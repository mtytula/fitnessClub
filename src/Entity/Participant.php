<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 * @ORM\Table(name="participants")
 */
class Participant
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
     * @ORM\Column(name="first_name",type="string", length=255)
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
     * @ORM\Column(type="string", length=255)
     */
    private $email;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Schedule", mappedBy="participants")
     *
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
     * @return Participant
     */
    public function setFirstName(string $firstName): Participant
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
     * @return Participant
     */
    public function setLastName(string $lastName): Participant
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Participant
     */
    public function setEmail(string $email): Participant
    {
        $this->email = $email;

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
     * @return Participant
     */
    public function setCreatedDate(\DateTime $createdDate): Participant
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
     * @return Participant
     */
    public function setUpdatedDate(\DateTime $updatedDate): Participant
    {
        $this->updatedDate = $updatedDate;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getSchedules(): ArrayCollection
    {
        return $this->schedules;
    }

    /**
     * @param Schedule $schedule
     * @return Participant|null
     */
    public function addToSchedule(Schedule $schedule): ?Participant
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->addParticipant($this);
        }

        return $this;
    }

    /**
     * @param Schedule $schedule
     * @return Participant|null
     */
    public function removeFromSchedule(Schedule $schedule): ?Participant
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);
            $schedule->removeParticipant($this);
        }

        return $this;
    }
}
