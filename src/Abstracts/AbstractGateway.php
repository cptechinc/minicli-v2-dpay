<?php namespace Dpay\Abstracts;
// Dplus Payments Model
use Payment;
// Payments Library
use Dpay\Data\PaymentResponse as Response;

/**
 * AbstractGateway
 * 
 * Contains Interface for Processing payment Requests
 * @property string   $errorMsg
 * @property Response $lastResponse
 */
abstract class AbstractGateway {
	const ENV_REQUIRED = [];

	public function init() {
		
	}
	public string $errorMsg;

/* =============================================================
	1. Contract Functions
============================================================= */
	/**
	 * Processes Request, sends Transaction Request to API
	 * @param  Payment $request
	 * @return Response
	 */
	public function process(Payment $request) : Response
	{
		$this->lastResponse = new Response();

		switch ($request->type) {
			case 'DEBIT':
				return $this->capture($request);
			case 'CPREAUTH':
				return $this->capturePreauthorized($request);
			case 'CREDIT':
				return $this->refund($request);
			case 'PREAUTH':
				return $this->preauthorize($request);
			case 'VOID':
				return $this->void($request);
			default:
				return $this->respondRequestNotSetup($request);
		}
	}

	/**
	 * Send Capture Request
	 * @param  Payment $paymentRequest
	 * @return Response
	 */
	protected function capture(Payment $paymentRequest) : Response
	{
		return $this->respondRequestNotSetup($paymentRequest);
	}

	/**
	 * Send Refund Request
	 * @param  Payment $paymentRequest
	 * @return Response
	 */
	protected function refund(Payment $paymentRequest) : Response
	{
		return $this->respondRequestNotSetup($paymentRequest);
	}

	/**
	 * Send Preauthorize Request
	 * @param  Payment $paymentRequest
	 * @return Response
	 */
	protected function preauthorize(Payment $paymentRequest) : Response
	{
		return $this->respondRequestNotSetup($paymentRequest);
	}

	/**
	 * Send Capture Preauthorized Request
	 * @param  Payment $paymentRequest
	 * @return Response
	 */
	protected function capturePreauthorized(Payment $paymentRequest) : Response
	{
		return $this->respondRequestNotSetup($paymentRequest);
	}

	/**
	 * Send Void Request
	 * @param  Payment $paymentRequest
	 * @return Response
	 */
	protected function void(Payment $paymentRequest) : Response
	{
		return $this->respondRequestNotSetup($paymentRequest);
	}

/* =============================================================
	2. API Requests
============================================================= */


/* =============================================================
	3. Responses
============================================================= */
	/**
	 * Return that API call is not set up
	 * @param  Payment $paymentRequest
	 * @return Response
	 */
	protected function respondRequestNotSetup(Payment $paymentRequest) : Response
	{
		$response = new Response();
		$response->ordn = $paymentRequest->getOrdernbr();
		$response->setApproved(false);
		$response->errorMsg = "$paymentRequest->type Request is not setup";
		return $response;
	}

	/**
	 * Return that API call is not set up
	 * @param  Payment $paymentRequest
	 * @return Response
	 */
	protected function respondApiNotConnected(Payment $paymentRequest) : Response
	{
		$response = new Response();
		$response->ordn = $paymentRequest->getOrdernbr();
		$response->setApproved(false);
		$response->errorMsg = "Could not connect to API";
		return $response;
	}

/* =============================================================
	4. Supplemental
============================================================= */
}
