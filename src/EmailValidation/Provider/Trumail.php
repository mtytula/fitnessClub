<?php

namespace App\EmailValidation\Provider;

use App\EmailValidation\ProviderInterface;
use App\Exception\GuzzleRequestException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class Trumail implements ProviderInterface
{
    const NAME = 'trumail';

    const API_URL = 'https://api.trumail.io/v2/lookups';

    const RESPONSE_FORMAT = 'JSON';

    const VALIDATION_NEEDLE = 'deliverable';

    /**
     * @var Client $client
     */
    private $client;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * Trumail constructor.
     * @param $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Response $response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse($response): void
    {
        $this->response = $response;
    }

    /**
     * @param string $email
     * @return bool
     * @throws Exception
     */
    public function validate(string $email): bool
    {
        $uri = sprintf('%s/%s?email=%s', self::API_URL, self::RESPONSE_FORMAT, $email);

        try {
            $request = new Request('GET', $uri, ['headers' => ['Accept' => 'application/json']]);
            $promise = $this->client->sendAsync($request)->then(
                function (Response $response) {
                    $this->response = $response->getBody();
                }
            );
            $promise->wait();
        } catch (ConnectException $connectException) {
            throw new ConnectException("Unable to send request!", $connectException->getRequest());
        } catch (ClientException $clientException) {
            throw new ClientException("Could not fetch response from API", $clientException->getRequest());
        }

        return $this->process(json_decode($this->response, true));
    }

    /**
     * @param array $arrayResponse
     * @return bool
     */
    public function process(array $arrayResponse): bool
    {
        if (in_array(self::VALIDATION_NEEDLE, array_keys($arrayResponse))) {
            return $arrayResponse[self::VALIDATION_NEEDLE] == 'true';
        }

        return false;
    }
}
