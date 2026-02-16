<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponses
{
    /**
     * رد النجاح (Success Response)
     */
    protected function success($data, $message = null, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Request was successful.',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * رد الخطأ (Error Response)
     */
    protected function error($data, $message = null, $code = 500): JsonResponse
    {
        return response()->json([
            'status' => 'Error has occurred.',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
