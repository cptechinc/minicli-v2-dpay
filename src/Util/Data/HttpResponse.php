<?php namespace Dpay\Util\Data;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\Data;

/**
 * HttpResponse
 * 
 * Container for HTTP Response data
 *
 * @property bool   $error    Did Error Occur?
 * @property string $message  Status Message
 * @property string $json     JSON Response
 * @property int    $httpCode HTTP Response Code
 * @property array  $headers  HTTP Response Headers
 * @property array  $jsonData
 */
class HttpResponse extends Data {
	public function __construct() {
		$this->error = false;
		$this->message = '';
		$this->json = '';
		$this->httpCode = 0;
		$this->headers = [];
		$this->jsonData = [];
	}
}
