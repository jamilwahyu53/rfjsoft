<?php

namespace App\Helpers;

class ServiceResponse
{
    /**
     * Success response
     */
    public static function success($data = [], $message = "Success")
    {
        return [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * Error response
     */
    public static function error($message = "Something went wrong", $errors = null)
    {
        return [
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ];
    }

    /**
     * Validation error response
     */
    public static function validationError($errors, $message = "Validation Error")
    {
        return [
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ];
    }
}
