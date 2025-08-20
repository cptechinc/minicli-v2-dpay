<?php namespace Dpay\AuthorizeNet;
// AuthorizeNet Library
use net\authorize\api\contract\v1 as AnetAPI;
// Dplus Payments Model
use Payment;
// Payments Library
use Dpay\Data\PaymentResponse as ResponseData;

/**
 * Response
 * 
 * @property AnetAPI\CreateTransactionResponse|null   $apiResponse       API Response
 * @property Payment                                  $paymentRequest    Payment Request
 * @property ResponseData                             $paymentResponse   Payment Response
 */
class Response {
	protected $apiResponse = null;
	protected $paymentRequest = null;
	protected $paymentResponse = null;

	public function __construct(AnetAPI\CreateTransactionResponse $apiResponse, Payment $paymentRequest) {
		$this->apiResponse    = $apiResponse;
		$this->paymentRequest = $paymentRequest;
	}

/* =============================================================
	1. Getters, Setters
============================================================= */
	/**
	 * Return API's Response
	 * @return AnetAPI\CreateTransactionResponse
	 */
	public function getApiResponse() : AnetAPI\CreateTransactionResponse|null
	{
		return $this->apiResponse;
	}

	/**
	 * Return Response
	 * @return ResponseData
	 */
	public function getPaymentResponse() : ResponseData
	{
		return $this->paymentResponse;
	}

/* =============================================================
	2. Public
============================================================= */
	/**
	 * Process Response from API
	 * @return bool
	 */
	public function process() : bool 
	{
		$this->paymentResponse = new ResponseData();
		$this->paymentResponse->ordn = $this->paymentRequest->getOrdernbr();
		$this->paymentResponse->type = $this->paymentRequest->getType();

		if ($this->apiResponse === null) {
			return $this->declineSimple(404, 'No Response');
		}

		// Check to see if the API request failed
		if ($this->apiResponse->getMessages()->getResultCode() != "Ok") {
			$this->processApiResponseError();
			return false;
		}

		// Since the API request was successful, look for a transaction response
		// and parse it to display the results of authorizing the card
		$tresponse = $this->apiResponse->getTransactionResponse();

		if ($tresponse === null || $tresponse->getMessages() === null) {
			return $this->processApiResponseError();
		}

		$this->paymentResponse->setApproved(true);
		$this->paymentResponse->transactionid = $tresponse->getTransId();
		$this->paymentResponse->authCode      = $tresponse->getAuthCode();
		$this->paymentResponse->avsCode       = $tresponse->getAvsResultCode();
		return true;
	}

/* =============================================================
	3. API Response Processing
============================================================= */
	/**
	 * Process Error Response
	 * @return bool
	 */
	protected function processApiResponseError() : bool
	{
		if ($this->apiResponse->getMessages()->getResultCode() == "Ok") {
			return true;
		}

		$tresponse = $this->apiResponse->getTransactionResponse();
		if ($tresponse === null || $tresponse->getMessages() === null) {
			return $this->declineSimple($this->apiResponse->getMessages()->getMessage()[0]->getCode(), $this->apiResponse->getMessages()->getMessage()[0]->getText());
		}
		return $this->declineSimple($tresponse->getErrors()[0]->getErrorCode(), $tresponse->getErrors()[0]->getErrorText());
	}

	/**
	 * Set Response to Declined
	 * @param  string $errorCode
	 * @param  string $msg
	 * @return false
	 */
	protected function declineSimple($errorCode, $msg) {
		$this->paymentResponse->setApproved(false);
		$this->paymentResponse->errorCode = $errorCode;
		$this->paymentResponse->errorMsg  = $msg;
		return false;
	}
}