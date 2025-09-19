<?php namespace Dpay\PayTrace\Api\Requests;
// Psr 
use Psr\Http\Message\ResponseInterface;
// Guzzle Http
use GuzzleHttp\Client as Http;
// Dpay
use Dpay\Util\Data\HttpResponse;
use Dpay\PayTrace\Environment;
use Dpay\PayTrace\Config;


/**
 * Request and Provide Oauth Token from Paytrace
 */
class Oauth extends AbstractRequest {
	const GRANT_TYPE = 'password';


	public function __construct() {
		$this->http = new Http(['base_uri' => Environment::API_URL, 'http_errors' => false]);
	}

	/**
	 * Return if Token was able to be generated
	 * @return bool
	 */
	public function generate() : bool
	{
		$success = $this->request();

		if ($success === false) {
			return false;
		}
		return $this->verify();
	}

	/**
	 * Request OAuth Token
	 * @return bool
	 */
	private function request() : bool 
	{
		$config = Config::instance();
		$this->response = new HttpResponse();
		$data = [
			'grant_type' => self::GRANT_TYPE,
			'username' => $config->apiLogin,
			'password' => $config->apiPassword
		];

		$result = $this->http->post(Environment::ENDPOINT_OAUTH, ['form_params' => $data]);
		$this->parseHttpResponse($result);
		return $this->response->error === false;
	}

	private function parseHttpResponse(ResponseInterface $result) {
		$this->response->httpCode = $result->getStatusCode();
		$this->response->json = (string) $result->getBody();

		$json = json_decode($this->response->json, true);

		if (array_key_exists('error', $json) && $json['error']) {
			$this->response->error   = true;
			$this->response->message = $json['error_description'];
		}
	}


	/**
	 * Verify Response and see if Token was provided
	 * @return bool
	 */
	private function verify() : bool
	{
		$response = $this->response;

		if (empty($response) || $response->error === true) {
			return false;
		}
		// If we reach here, we have been able to communicate with the service,
		// next is decode the json response and then review Http Status code of the request
		// and move forward with further request.

		if ($response->httpCode != 200) {
			return false;
		}
		
		$json  = json_decode($response->json, true);
		$this->token = $json['access_token'];
		return true;
	}

	
}
