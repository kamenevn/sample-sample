<?php

namespace App\Services\Microservices;

use App\Traits\ResponseTrait;
use GuzzleHttp\ClientInterface;

class BaseHttpService
{
    use ResponseTrait;

    /** @var ClientInterface */
    protected $httpClient;

    /** @var string */
    protected $host;

    /**
     * @param ClientInterface $httpClient
     * @param string $host
     */
    public function __construct(ClientInterface $httpClient, string $host)
    {
        $this->httpClient = $httpClient;
        $this->host = $host;
    }
}
