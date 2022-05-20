<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * @license Apache 2.0
     */

    /**
     * @OA\Info(
     *     version="1.0.0",
     *     title="Sentegrate API Documentation",
     *
     * )
     */

    public function sendResponse($result, $message): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }

    public function sendError($error, $message = [], $code = 404): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error
        ];

        if (!empty($message)) {
            $response['data'] = $message;
        }
        return response()->json($response, $code);
    }

    public function exceptionHandler($result, $code = 500): \Illuminate\Http\JsonResponse
    {
        $response = [
            'error' => true,
            'message' => $result,
            'data' => null
        ];

        return response()->json($response, $code);
    }
}
