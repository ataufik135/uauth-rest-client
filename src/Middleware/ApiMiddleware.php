<?php

namespace TaufikT\UAuthRestClient\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Taufik\UAuthRestClient\Services\ApiService;

class ApiMiddleware
{
  private $apiService;

  public function __construct(ApiService $apiService)
  {
    $this->apiService = $apiService;
  }

  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next): Response
  {
    $apiKey = $this->extractApiKey($request);

    if (!$apiKey) {
      return response()->json(['message' => 'API key is required for authentication.'], 401);
    }

    $requestIP = $request->getClientIp();
    $providerId = config('uauth.api.provider_id');

    $route = $request->route();
    if (!$route) {
      return response()->json(['message' => 'You do not have permission to access this resource.'], 403);
    }

    $requestSignature = $request->method() . ':/' . $route->uri();

    $response = $this->apiService->get('key-verify', [
      'provider_id' => $providerId,
      'request_ip' => $requestIP,
      'permission' => $requestSignature
    ], $apiKey);

    $data = $response->json();
    if ($response->successful() && $data['status'] === 'key_valid') {
      return $next($request);
    }

    return response()->json($data, $response->status());
  }

  private function extractApiKey(Request $request): ?string
  {
    if ($apiKey = $request->header('X-API-Key')) {
      return $apiKey;
    }

    if ($auth = $request->header('Authorization')) {
      if (str_starts_with($auth, 'Bearer ')) {
        return substr($auth, 7);
      }
    }

    return $request->query('api_key');
  }
}
