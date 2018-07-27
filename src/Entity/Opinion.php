<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="opinions")
 */
class Opinion
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
    private $content;

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
     * @var Activity
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="opinions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activity;

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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Opinion
     */
    public function setContent(string $content): Opinion
    {
        $this->content = $content;

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
     * @return Opinion
     */
    public function setCreatedDate(\DateTime $createdDate): Opinion
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
     * @return Opinion
     */
    public function setUpdatedDate(\DateTime $updatedDate): Opinion
    {
        $this->updatedDate = $updatedDate;
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
     * @return Opinion
     */
    public function setActivity(Activity $activity): Opinion
    {
        $this->activity = $activity;

        return $this;
    }
}
