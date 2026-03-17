<?php

namespace Shared\Exceptions;

use Exception;

class ApiJsonException extends Exception
{
    /**
     * API exception constructor returning a JSON response.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = 'Server error', int $code = 500)
    {
        parent::__construct($message, $code);
    }

    /**
     * Render the exception as a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => $this->getMessage(),
        ], $this->getCode());
    }
}
