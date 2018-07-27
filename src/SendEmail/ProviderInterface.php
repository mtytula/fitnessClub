<?php

namespace App\SendEmail;

interface ProviderInterface
{
    /**
     * @param string $deliveryAddress
     * @param array $data
     * @return bool
     */
    public function sendAssignEmail(string $deliveryAddress, array $data): bool;
}
