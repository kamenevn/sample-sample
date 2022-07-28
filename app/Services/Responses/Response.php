<?php

namespace App\Services\Responses;

use RuntimeException;

/**
 * Class Response
 * @package App\Services\Responses
 */
class Response
{
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;

        if ($this->isEmptyResponse()) {
            throw new RuntimeException('Empty response');
        }
    }

    /**
     * Если запрос прошел успешно
     * @return boolean
     */
    public function isSuccess(): bool
    {
        return $this->response['status'] === 'success';
    }

    /**
     * Если ошибка
     * @return boolean
     */
    public function isFail(): bool
    {
        return $this->response['status'] === 'error';
    }

    /**
     * If empty response
     * @return boolean
     */
    public function isEmptyResponse(): bool
    {
        if (empty($this->response)) {
            return true;
        }

        if (!isset($this->response['status'])) {
            return true;
        }

        if (empty($this->response['status'])) {
            return true;
        }

        return false;
    }

    /**
     * Get error message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->response['message'];
    }

    /**
     * Get DATA
     * @return mixed
     */
    public function getData()
    {
        return $this->response['data'];
    }
}
