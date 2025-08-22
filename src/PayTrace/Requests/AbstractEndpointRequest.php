<?php namespace Dpay\PayTrace\Requests;
// Guzzle Http
use GuzzleHttp\Client as Http;
// Dpay
use Dpay\Util\Data\HttpResponse;
use Dpay\PayTrace\Environment;

class AbstractEndpointRequest extends AbstractRequest {
	const ENDPOINTPATH = '';

    public function __construct($token) {
		$this->token = $token;
        $this->http  = $this->createHttpClient();
	}

/* =============================================================
	Getters
============================================================= */
    /**
	 * Return Bearer Token Value
	 * @return string
	 */
	protected function bearerToken() : string
	{
		return sprintf("Bearer %s", $this->token);
	}

	/**
	 * Return Path to endpoint
	 * @param  string $endpoint
	 * @return string
	 */
	protected function getEndpointPath(string $endpoint) : string
	{
		$parts = [Environment::API_VERSION, self::ENDPOINTPATH, ltrim($endpoint, '/')];
		return '/' . implode("/", $parts);
	}

/* =============================================================
	Public
============================================================= */
	/**
	 * Send POST Request to Endpoint
	 * @param  string $endpoint Endpoint Path
	 * @param  array  $data     Request Data
	 * @return HttpResponse
	 */
	public function post(string $endpoint, $data = []) : HttpResponse
	{
		$this->response = new HttpResponse();

		$result = $this->http->post($this->getEndpointPath($endpoint), [
			'form_params' => $data
		]);
		$this->processHttpResponse($result);
		return $this->response;
	}

/* =============================================================
	Internal
============================================================= */
	/**
	 * Create Client for HTTP requests
	 * @return Http
	 */
    protected function createHttpClient() : Http {
        
		$headers = [
			'Content-type' => 'application/json',
			'Authorization' => $this->bearerToken(),
			'Cache-Control' => 'no-cache'
		];
		return new Http(['base_uri' => Environment::API_URL, 'http_errors' => false, 'headers' => $headers]);
    }
}