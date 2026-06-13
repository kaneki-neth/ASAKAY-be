<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Capture basic request/response info
        $logData = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'request_payload' => $request->except(['password', 'password_confirmation', 'current_password', 'new_password']),
            'response_status' => $response->getStatusCode(),
        ];

        // Attempt to decode response payload if it's JSON
        $content = $response->getContent();
        $decodedContent = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $logData['response_payload'] = $decodedContent;
        } else {
            // If not JSON, just log a snippet or indicate type
            $logData['response_payload_raw'] = mb_substr($content, 0, 1000);
        }

        Log::info("API Activity: {$request->method()} {$request->path()}", $logData);

        return $response;
    }
}
