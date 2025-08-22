<?php namespace Dpay\PayTrace;
// Dplus Payments Model
use Payment;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\EnvVarsReader as EnvVars;
// Dpay
use Dpay\Data\PaymentResponse as ResponseData;
use Dpay\Abstracts\AbstractGateway;
use Dpay\Services\Logger;

/**
 * Gateway
 * 
 * Wrapper For making API calls to PayTrace
 * 
 * @property string $token API Token
 */
class Gateway extends AbstractGateway {
	const ENV_REQUIRED = [
		'PAYTRACE.API.LOGIN',
		'PAYTRACE.API.KEY',
		'PAYTRACE.API.INTEGRATOR.ID'
	];

/* =============================================================
	1. Contract Functions
============================================================= */
	public function process(Payment $request) : ResponseData
	{
		$success = $this->initOauth();

		if ($success === false) {
			return $this->respondOauthfailed($request);
		}
		return parent::process($request);
	}

	protected function refund(Payment $paymentRequest) : ResponseData
	{
		$transaction = new Transactions\Refund($paymentRequest);
		return $this->processTransaction($transaction);
	}

	protected function capture(Payment $paymentRequest) : ResponseData
	{
		$transaction = new Transactions\Capture($paymentRequest);
		return $this->processTransaction($transaction);
	}

	protected function void(Payment $paymentRequest) : ResponseData
	{
		$transaction = new Transactions\VoidTransaction($paymentRequest);
		return $this->processTransaction($transaction);
	}

	protected function preauthorize(Payment $paymentRequest) : ResponseData
	{
		$transaction = new Transactions\Preauthorize($paymentRequest);
		return $this->processTransaction($transaction);
	}

	protected function capturePreauthorized(Payment $paymentRequest) : ResponseData
	{
		$transaction = new Transactions\CapturePreauthorized($paymentRequest);
		return $this->processTransaction($transaction);
	}

/* =============================================================
	2. API Requests
============================================================= */
	/**
	 * Send Oauth Request to get Token
	 * @return bool
	 */
	private function initOauth() : bool
	{
		$auth = new Requests\Oauth();
		$hasToken = $auth->generate();

		if ($hasToken === false) {
			return false;
		}
		$this->token = $auth->getToken();
		return true;
	}

	/**
	 * Send Request to API
	 * @param  Transactions\AbstractTransaction $data
	 * @return ResponseData
	 */
	private function processTransaction(Transactions\AbstractTransaction $transaction) : ResponseData
	{
		$request = new Requests\TransactionRequest($this->token);
		$results = $request->post($transaction::ENDPOINT, $transaction->generate());
		

		$response = new ResponseParser($results, $transaction->getPaymentRequest());
		$response->process();
		$this->logResponseDebug($response);
		return $response->getPaymentResponse();
	}

/* =============================================================
	3. Responses
============================================================= */
	/**
	 * Return that API call is not set up
	 * @param  Payment $paymentRequest
	 * @return ResponseData
	 */
	private function respondOauthfailed(Payment $paymentRequest) : ResponseData {
		$response = new ResponseData();
		$response->ordn = $paymentRequest->getOrdernbr();
		$response->setApproved(false);
		$response->errorCode = 400;
		$response->errorMsg = "Failed to initiate OAuth";
		return $response;
	}

	private function logResponseDebug(ResponseParser $r) : void
	{
		if (EnvVars::getBool('LOG.DEBUG') === false) {
			return;
		}

		$paymentRequest = $r->getPaymentRequest();

		$data = [
			$paymentRequest->getOrdernbr(),
			$paymentRequest->type,
			'Transaction Response:' . $r->getApiResponse()->json,
		];
		$this->log->debug(Logger::createLogString($data));
	}
}