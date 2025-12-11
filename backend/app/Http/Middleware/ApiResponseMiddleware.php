<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only format JSON responses for API routes
        if ($request->is('api/*') && $response->headers->get('Content-Type') === 'application/json') {
            $content = json_decode($response->getContent(), true);

            // If response is already in our format, don't modify it
            if (isset($content['success'])) {
                return $response;
            }

            // Format Laravel's default responses to our API format
            if ($response->getStatusCode() >= 400) {
                $formatted = [
                    'success' => false,
                    'message' => $content['message'] ?? 'An error occurred',
                    'errors' => $content['errors'] ?? null,
                ];
            } else {
                $formatted = [
                    'success' => true,
                    'message' => 'Success',
                    'data' => $content,
                ];
            }

            return response()->json($formatted, $response->getStatusCode());
        }

        return $response;
    }
}

