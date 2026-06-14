<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;

if (!function_exists('sendSuccessResponse')) {
    /**
     * Return a standardized JSON success response.
     */
    function sendSuccessResponse(string $message, mixed $data = [], int $status = 200, int $statusCode = 0): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $statusCode > 0 ? $statusCode : $status);
    }
}
