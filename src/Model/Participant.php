<?php

namespace App\Model;

use App\Entity\Participant as ParticipantEntity;

class Participant
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
    public $email;

    /**
     * @param ParticipantEntity $participant
     * @return Participant
     */
    public function fromParticipant(ParticipantEntity $participant): Participant
    {
        $participantRequest = new Participant();
        $participantRequest->firstName = $participant->getFirstName();
        $participantRequest->lastName = $participant->getLastName();
        $participantRequest->email = $participant->getEmail();

        return $participantRequest;
    }
}
