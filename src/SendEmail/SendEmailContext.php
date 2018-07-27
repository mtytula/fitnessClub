<?php

namespace App\SendEmail;

class SendEmailContext
{
    /**
     * @var ProviderInterface $provider
     */
    private $provider;

    /**
     * SendEmailContext constructor.
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return ProviderInterface
     */
    public function getProvider(): ProviderInterface
    {
        return $this->provider;
    }

    /**
     * @param string $deliveryAddress
     * @param array $data
     * @return bool
     */
    public function sendAssignEmail(string $deliveryAddress, array $data): bool
    {
        return $this->provider->sendAssignEmail($deliveryAddress, $data);
    }
}
