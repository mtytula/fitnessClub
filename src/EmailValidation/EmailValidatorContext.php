<?php

namespace App\EmailValidation;

use App\EmailValidation\Exception\ProviderException;

class EmailValidatorContext
{
    /**
     * @var ProviderInterface $provider
     */
    private $provider;

    /**
     * @return ProviderInterface
     */
    public function getProvider(): ProviderInterface
    {
        return $this->provider;
    }

    /**
     * @param ProviderInterface $provider
     * @return void
     */
    public function setProvider(ProviderInterface $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function validate(string $email): bool
    {
        $validationResult = $this->provider->validate($email);

        return is_bool($validationResult) ? $validationResult : false;
    }
}
