<?php namespace Dpay\PayTrace;
// Dplus Payments Model
use Payment;
// Dpay
use Dpay\Util\Data\HttpResponse;
use Dpay\Data\PaymentResponse as ResponseData;
/**
 * Response
 * Wrapper for Processing PayTrace API Response
 * @property HttpResponse  $apiResponse      API Response
 * @property Payment       $paymentRequest   Payment Request
 * @property ResponseData  $paymentResponse  Payment Response
 */
class ResponseParser {
	private HttpResponse $apiResponse;
	private Payment $paymentRequest;
	private ResponseData $paymentResponse;

	public function __construct(HttpResponse $apiResponse, Payment $paymentRequest) {
		$this->apiResponse = $apiResponse;
		$this->paymentRequest = $paymentRequest;
	}

/* =============================================================
	1. Getters, Setters
============================================================= */
	/**
	 * Return API Response
	 * @return HttpResponse
	 */
	public function getApiResponse() : HttpResponse
	{
		return $this->apiResponse;
	}

	/**
	 * Return Parsed Response
	 * @return ResponseData
	 */
	public function getPaymentResponse() : ResponseData
	{
		return $this->paymentResponse;
	}

	/**
	 * Return Payment Request
	 * @return Payment
	 */
	public function getPaymentRequest() : Payment
	{
		return $this->paymentRequest;
	}


/* =============================================================
	2. Public
============================================================= */	
	/**
	 * Parse Response from API Response
	 * @return bool
	 */
	public function process() : void
	{
		$this->paymentResponse = new ResponseData();
		$this->paymentResponse->ordn = $this->paymentRequest->getOrdernbr();
		$this->paymentResponse->type = $this->paymentRequest->type;

		if ($this->apiResponse->error == true) {
			return $this->declineSimple($this->apiResponse->httpCode, $this->apiResponse->message);
		}

		if (empty($this->apiResponse->jsonData)) {
			return $this->declineSimple(404, 'No Response from API');
		}

		if ($this->apiResponse->jsonData['success'] === false) {
			return $this->processApiResponseError();
		}

		return $this->processApiResponseSuccess();
	}

/* =============================================================
	3. API Response Processing
============================================================= */
	/**
	 * Parse Error API Response
	 * @return void
	 */
	private function processApiResponseError() : void
	{
		$json = $this->apiResponse->jsonData;

		if (array_key_exists('transaction_id', $json)) {
			$this->paymentResponse->transactionid = $json['transaction_id'];
		}

		if (array_key_exists('errors', $json)) {
			$errors = $json['errors'];
			$codes  = array_keys($errors);
			$msgs   = array_values($errors);
			return $this->declineSimple($codes[0], $msgs[0][0]);
		}

		$errorCode = array_key_exists('response_code', $json) ? $json['response_code'] : 404;
		$msg  = array_key_exists('status_message', $json) ? $json['status_message'] : 'No Response';    
		return $this->declineSimple($errorCode, $msg);
	}

	/**
	 * Parse Successful API REsponse
	 * @return void
	 */
	private function processApiResponseSuccess() : void 
	{
		$json = $this->apiResponse->jsonData;

		$this->paymentResponse->setApproved(true);
		$this->paymentResponse->transactionid = $json['transaction_id'];

		if (array_key_exists('approval_code', $json)) {
			$this->paymentResponse->authCode = $json['approval_code'];
		}

		if (array_key_exists('avs_response', $json)) {
			$this->paymentResponse->avsCode = $json['avs_response'];
		}
	}

	/**
	 * Set Response to Declined
	 * @param  string $errorCode
	 * @param  string $msg
	 * @return void
	 */
	private function declineSimple($errorCode, $msg) : void
	{
		$this->paymentResponse->setApproved(false);
		$this->paymentResponse->errorCode = $errorCode;
		$this->paymentResponse->errorMsg  = $msg;
	}
}