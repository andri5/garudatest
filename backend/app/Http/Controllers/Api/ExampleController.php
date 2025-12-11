<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    use ApiResponse;

    /**
     * Example success response
     */
    public function success(): JsonResponse
    {
        return $this->successResponse([
            'message' => 'This is a success response example',
        ], 'Operation successful');
    }

    /**
     * Example 404 response
     */
    public function notFound(): JsonResponse
    {
        return $this->notFoundResponse('Resource not found');
    }

    /**
     * Example validation error
     */
    public function validationError(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        return $this->successResponse(null, 'Validation passed');
    }
}

