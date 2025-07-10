<?php

namespace Taufik\UAuthRestClient\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\PendingRequest;

class ApiService
{
  private string $baseUrl;
  private bool $sslVerify;
  private int $timeout;
  private int $connectTimeout;
  private array $customHeaders = [];

  public function __construct()
  {
    $this->baseUrl = config('uauth.api.base_url');
    $this->sslVerify = config('uauth.api.ssl_verify');
    $this->timeout = config('uauth.api.timeout');
    $this->connectTimeout = config('uauth.api.connect_timeout');
  }

  /**
   * Get configured HTTP client
   */
  private function getClient(): PendingRequest
  {
    return Http::baseUrl($this->baseUrl)
      ->timeout($this->timeout)
      ->connectTimeout($this->connectTimeout)
      ->withOptions([
        'verify' => $this->sslVerify,
      ]);
  }

  /**
   * Get client with authentication
   */
  private function getAuthenticatedClient(?string $token = null): PendingRequest
  {
    $client = $this->getClient();

    if ($token) {
      $client->withToken($token);
    }

    return $client;
  }

  /**
   * GET request
   */
  public function get(string $endpoint, array $params = [], ?string $token = null): Response
  {
    if (filter_var($endpoint, FILTER_VALIDATE_URL)) {
      $client = Http::timeout($this->timeout)
        ->connectTimeout($this->connectTimeout)
        ->withOptions([
          'verify' => $this->sslVerify
        ]);

      if ($token) {
        $client->withToken($token);
      }

      return $client->get($endpoint, $params);
    }

    return $this->getAuthenticatedClient($token)
      ->get($endpoint, $params);
  }

  /**
   * POST request
   */
  public function post(string $endpoint, array $data = [], ?string $token = null): Response
  {
    return $this->getAuthenticatedClient($token)
      ->post($endpoint, $data);
  }
  public function postFormAcceptJson(string $endpoint, array $data = [], ?string $token = null): Response
  {
    return $this->getAuthenticatedClient($token)
      ->acceptJson()
      ->asForm()
      ->post($endpoint, $data);
  }
  public function attachPostAcceptJson(string $endpoint, $file, array $data = [], ?string $token = null): Response
  {
    return $this->getAuthenticatedClient($token)
      ->acceptJson()
      ->attach(
        'file',
        file_get_contents($file->getRealPath()),
        $file->getClientOriginalName()
      )
      ->post($endpoint, $data);
  }

  /**
   * PUT request
   */
  public function put(string $endpoint, array $data = [], ?string $token = null): Response
  {
    return $this->getAuthenticatedClient($token)
      ->put($endpoint, $data);
  }

  /**
   * DELETE request
   */
  public function delete(string $endpoint, ?string $token = null): Response
  {
    return $this->getAuthenticatedClient($token)
      ->delete($endpoint);
  }

  /**
   * Upload file
   */
  public function upload(string $endpoint, array $files, array $data = [], ?string $token = null): Response
  {
    return $this->getAuthenticatedClient($token)
      ->attach($files)
      ->post($endpoint, $data);
  }

  /**
   * Request with custom headers
   */
  public function withHeaders(array $headers): PendingRequest
  {
    return $this->getClient()->withHeaders($headers);
  }

  /**
   * Custom request with headers
   */
  public function request(string $method, string $endpoint, array $options = []): Response
  {
    $client = $this->getClient();

    if (isset($options['headers'])) {
      $client->withHeaders($options['headers']);
    }

    if (isset($options['token'])) {
      $client->withToken($options['token']);
    }

    return $client->send($method, $endpoint, $options);
  }
}
