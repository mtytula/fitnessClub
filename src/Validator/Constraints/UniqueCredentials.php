<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueCredentials extends Constraint
{
    public $message = '{{ string }} already taken';
}
