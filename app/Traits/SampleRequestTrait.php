<?php

namespace App\Traits;

use App\Services\Microservices\Sample\SampleServiceInterface;
use Illuminate\Container\Container;

trait SampleRequestTrait
{
    /**
     * @return array
     */
    public function getSampleRequest(): array
    {
        $container = Container::getInstance()->make(SampleServiceInterface::class);
        $result = $container->sampleRequest();

        return $result->getSampleResponseData();
    }
}
