<?php

namespace App\Services\Responses\Sample;

use App\Services\Responses\Response;

class SampleResponse extends Response
{
    /**
     * @return array
     */
    public function getSampleResponseData(): array
    {
        if (empty($this->getData())) {
            return [];
        }

        return json_decode($this->getData(), true);
    }
}
