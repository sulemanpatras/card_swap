<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    protected function sendResponse($result, $message): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $result,
            'message' => $message
        ], 200);
    }

    protected function sendError($error, $code = 404): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $error,
        ], $code);
    }

    protected function sendValidateError($error, $code = 422): JsonResponse
{
    $concatenatedMessages = implode(', ', $error->all());

    return response()->json([
        'success' => false,
        'message' => $concatenatedMessages,
    ], $code);
}


}
