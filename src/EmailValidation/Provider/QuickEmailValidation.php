<?php

namespace App\EmailValidation\Provider;

use App\EmailValidation\ProviderInterface;
use QuickEmailVerification\Client;

class QuickEmailValidation implements ProviderInterface
{
    const NAME = 'quickemailvalidation';

    const API_KEY = '%quick_email_validation_api_key%';

    const VALIDATION_NEEDLE = 'result';

    const PASS_TERM = 'valid';

    /**
     * @var Client $client
     */
    private $client;

    /**
     * QuickEmailValidation constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function validate(string $email): bool
    {
        $quickEmailVerification = $this->client->quickemailverification();

        return $this->process($quickEmailVerification->verify($email)->body);
    }

    /**
     * @param array $arrayResponse
     * @return bool
     */
    public function process(array $arrayResponse): bool
    {
        if (in_array(self::VALIDATION_NEEDLE, array_keys($arrayResponse))) {
            return $arrayResponse[self::VALIDATION_NEEDLE] === self::PASS_TERM;
        }

        return false;
    }
}
