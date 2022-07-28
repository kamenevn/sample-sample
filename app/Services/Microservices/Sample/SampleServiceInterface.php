<?php

namespace App\Services\Microservices\Sample;

use App\Services\Responses\Sample\SampleResponse;

interface SampleServiceInterface
{
    /**
     * @return SampleResponse
     */
    public function sampleRequest(): SampleResponse;
}
