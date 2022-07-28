<?php


namespace App\Services\Microservices;

use SmartHttp\Client as SmartClient;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpClient
 * @package App\Services\Microservices
 */
class HttpClient extends SmartClient
{
    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function get(string $uri, array $data = [], array $headers = []): array
    {
        return $this->fetchResponseContents(
            parent::get($uri, [
                'headers' => $headers,
                'query' => $data
            ])
        );
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function put(string $uri, array $data = [], array $headers = []): array
    {
        return $this->fetchResponseContents(
            parent::put($uri, [
                'headers' => $headers,
                'form_params' => $data
            ])
        );
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function post(string $uri, array $data = [], array $headers = []): array
    {
        return $this->fetchResponseContents(
            parent::post($uri, [
                'headers' => $headers,
                'form_params' => $data
            ])
        );
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    private function fetchResponseContents(ResponseInterface $response): array
    {
        $contents = json_decode($response->getBody()->getContents(), JSON_UNESCAPED_UNICODE);

        if (empty($contents)) {
            throw new \RuntimeException('Empty request contents');
        }

        return $contents;
    }
}
