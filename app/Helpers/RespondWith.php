<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RespondWith
{
    public static function success($data = [], string $message = 'Successful', int $code = Response::HTTP_OK, array $meta = []) : JsonResponse
    {
        $payload = ['status' => 'success', 'message' => $message, 'data' => $data, 'meta' => $meta];
        return response()->json($payload, $code);
    }

    public static function error($errors = [], string $message = 'Error', int $code = Response::HTTP_INTERNAL_SERVER_ERROR, array $meta = []) : JsonResponse
    {
        $payload = ['status' => 'error', 'message' => $message, 'errors' => $errors, 'meta' => $meta];
        return response()->json($payload, $code);
    }
}
