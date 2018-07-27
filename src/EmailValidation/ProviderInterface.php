<?php

namespace App\EmailValidation;

interface ProviderInterface
{
    /**
     * @param string $email
     * @return bool
     */
    public function validate(string $email):bool;

    /**
     * @param array $responseArray
     * @return bool
     */
    public function process(array $responseArray):bool;
}
