<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogQuilWebhookRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $requestId = uniqid('quil_', true);

        // Log incoming request
        Log::channel('quil_webhooks')->info('=== INCOMING QUIL WEBHOOK ===', [
            'request_id' => $requestId,
            'timestamp' => now()->toIso8601String(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers' => $this->sanitizeHeaders($request->headers->all()),
            'payload_size' => strlen($request->getContent()),
        ]);

        // Log payload (with size limit for large payloads)
        $payload = $request->all();
        Log::channel('quil_webhooks')->debug('Webhook Payload', [
            'request_id' => $requestId,
            'payload' => $this->limitPayloadSize($payload),
        ]);

        // Process the request
        $response = $next($request);

        // Calculate processing time
        $processingTime = round((microtime(true) - $startTime) * 1000, 2);

        // Log response
        Log::channel('quil_webhooks')->info('=== WEBHOOK RESPONSE ===', [
            'request_id' => $requestId,
            'status_code' => $response->getStatusCode(),
            'processing_time_ms' => $processingTime,
            'response_body' => $this->getResponseContent($response),
        ]);

        // Log performance warning if slow
        if ($processingTime > 2000) {
            Log::channel('quil_webhooks')->warning('Slow webhook processing detected', [
                'request_id' => $requestId,
                'processing_time_ms' => $processingTime,
            ]);
        }

        return $response;
    }

    /**
     * Sanitize headers to remove sensitive information.
     */
    private function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = ['authorization', 'x-api-key', 'cookie'];
        
        foreach ($sensitiveHeaders as $header) {
            if (isset($headers[$header])) {
                $headers[$header] = ['***REDACTED***'];
            }
        }
        
        return $headers;
    }

    /**
     * Limit payload size for logging to prevent memory issues.
     */
    private function limitPayloadSize($payload, int $maxDepth = 5): array|string
    {
        if (!is_array($payload)) {
            return $payload;
        }

        // Convert to JSON and check size
        $json = json_encode($payload);
        $size = strlen($json);

        // If payload is larger than 50KB, truncate it
        if ($size > 50000) {
            return [
                '_truncated' => true,
                '_original_size' => $size,
                '_message' => 'Payload truncated for logging. See full payload in request.',
                'event_id' => $payload['id'] ?? null,
                'eventType' => $payload['eventType'] ?? null,
                'meeting_id' => $payload['data']['meeting']['id'] ?? null,
                'meeting_name' => $payload['data']['meeting']['name'] ?? null,
            ];
        }

        return $payload;
    }

    /**
     * Get response content safely.
     */
    private function getResponseContent(Response $response): mixed
    {
        $content = $response->getContent();
        
        if (empty($content)) {
            return null;
        }

        $decoded = json_decode($content, true);
        return $decoded ?? $content;
    }
}
