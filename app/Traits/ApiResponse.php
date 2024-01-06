<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success(array $data = null, int $code = 200): JsonResponse
    {
        $response = config('response.successfully');
        $response['data'] = $data;
        return response()->json($response, $code);
    }
}
