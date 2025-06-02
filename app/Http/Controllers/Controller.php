<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function success($payload,$message = "Success response" ,$responseCode = 200, $status = true)
    {
        $response = [
            'responseCode' => $responseCode,
            'message' => $message,
            'status' => $status,
            'payload' => $payload
        ];
        return response()->json($response, $responseCode);
    }

        protected function error(string $message = null, int $responseCode, $payload = null)
    {
        return response()->json([
            'responseCode' => $responseCode,
            'status' => 'Error',
            'message' => $message,
            'payload' => $payload
        ], $responseCode);
    }
}
