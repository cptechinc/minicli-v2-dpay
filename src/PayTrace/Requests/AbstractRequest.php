<?php namespace Dpay\PayTrace\Requests;
// Psr
use Psr\Http\Message\ResponseInterface;
// Guzzle Http
use GuzzleHttp\Client as Http;
// Dpay
use Dpay\Util\Data\HttpResponse;

class AbstractRequest {
    protected Http $http;
    protected HttpResponse $response;
	protected string $token;

/* =============================================================
	Getters
============================================================= */
    /**
	 * Return if Token is Defined
	 * @return bool
	 */
	public function hasToken() : bool
	{
		return empty($this->token) === false;
	}

	/**
	 * Return Token
	 * @return string
	 */
	public function getToken() : string
	{
		return $this->token;
	}
    
/* =============================================================
	Getters
============================================================= */

/* =============================================================
	Internal
============================================================= */

	/**
	 * Process API Response, set Response details
	 * @param  ResponseInterface $result  API Response
	 * @return void
	 */
	protected function processHttpResponse(ResponseInterface $result) : void
	{
		if (empty($result)) {
			$this->response->error = true;
			$this->response->message = 'Request failed';
		}
		$this->response->httpCode = $result->getStatusCode();
		$this->response->json     = (string) $result->getBody();
		$this->response->jsonData = json_decode($this->response->json, true);

		if (array_key_exists('error', $this->response->jsonData) && $this->response->jsonData['error']) {
			$this->response->error   = true;
			$this->response->message = $this->response->jsonData['error_description'];
		}
	}
}