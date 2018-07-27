<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class EmailApi extends Constraint
{
    public $message = '{{ string }} seems to be a wrong email address';
}
