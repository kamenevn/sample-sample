<?php

namespace App\Services\Microservices\Sample;

use App\Exceptions\HttpException;
use App\Services\Microservices\BaseHttpService;
use App\Services\Responses\Sample\SampleResponse;

class SampleApiService extends BaseHttpService implements SampleServiceInterface
{
    private const BASE_PATH = '/sample/url/';

    /**
     * @return SampleResponse
     * @throws HttpException
     */
    public function sampleRequest(): SampleResponse
    {
        try {
            $response = new SampleResponse(
                $this->httpClient->post($this->host . self::BASE_PATH, [])
            );

            if ($response->isFail()) {
                throw new HttpException($response->getMessage());
            }

            return $response;
        } catch (\Throwable $exception) {
            $message = sprintf('%d %s in %s:%s', $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
            throw new HttpException($message, 103);
        }
    }
}
