<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     * @param string $message
     * @param mixed $data
     * @param int $errorCode
     * @param string $status
     * @return JsonResponse
     */
    public function response(string $message, $data = null, int $errorCode = 0, string $status = 'success'): JsonResponse
    {
        return response()->json([
            'error_code'    =>  $errorCode,
            'status'        =>  $status,
            'message'       =>  $message,
            'data'          =>  $data,
        ]);
    }
}
