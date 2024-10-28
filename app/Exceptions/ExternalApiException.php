<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ExternalApiException extends Exception
{
    /**
     * Render the exception as a JSON response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => $this->getMessage()
        ], 500);
    }
}
