<?php

namespace App\Model;

use \App\Entity\Coach as CoachEntity;
use Symfony\Component\HttpFoundation\File\File;

class Coach
{
    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $picture;

    /**
     * @var string
     */
    public $description;

    /**
     * @param CoachEntity $coach
     * @return Coach
     */
    public function fromCoach(CoachEntity $coach): Coach
    {
        $coachRequest = new Coach();
        $coachRequest->firstName = $coach->getFirstName();
        $coachRequest->lastName = $coach->getLastName();
        $coachRequest->description = $coach->getDescription();
        if (file_exists('uploads/'.$coach->getPicture())) {
            $coachRequest->picture = new File('uploads/'.$coach->getPicture());
        } else {
            $coachRequest->picture = $coach->getPicture();
        }
        return $coachRequest;
    }
}
