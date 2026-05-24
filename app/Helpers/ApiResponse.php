<?php

namespace App\Helpers;

class ApiResponse
{
    /**
     * Success response
     */
    public static function success($data = [], $message = "Success", $status = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Error response
     */
    public static function error($message = "Something went wrong", $errors = null, $status = 500)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }

    /**
     * Validation error response
     */
    public static function validationError($errors, $message = "Validation Error", $status = 422)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}
