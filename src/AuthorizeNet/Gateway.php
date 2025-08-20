<?php namespace Dpay\AuthorizeNet;
// AuthorizeNet Library
use net\authorize\api\contract\v1 as AnetAPI;
// Dplus Payments Model
use Payment;
// Lib
use Dpay\Data\PaymentResponse as ResponseData;
use Dpay\AbstractGateway;
use Dpay\AuthorizeNet\Response as TransactionResponse;

/**
 * Gateway
 * 
 * Wrapper For making API calls to Authorize.Net
 * @property Config $config 
 */
class Gateway extends AbstractGateway {
	const ENV_REQUIRED = [
		'AUTHORIZENET.API.LOGIN',
		'AUTHORIZENET.API.KEY'
	];

	public function init() {
		$this->config = Config::instance();
	}

/* =============================================================
	1. Contract Functions
============================================================= */
	protected function capture(Payment $paymentRequest) : ResponseData
	{
		$transaction = new Transactions\Capture($paymentRequest);
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

	protected function void(Payment $paymentRequest) : ResponseData
	{
		$transaction = new Transactions\VoidTransaction($paymentRequest);
		return $this->processTransaction($transaction);
	}

	protected function refund(Payment $paymentRequest) : ResponseData
	{
		$transaction = new Transactions\Refund($paymentRequest);
		return $this->processTransaction($transaction);
	}

/* =============================================================
	2. API Requests
============================================================= */
	/**
	 * Send Request to API
	 * @param  Transactions\AbstractTransaction $data
	 * @return ResponseData
	 */
	private function processTransaction(Transactions\AbstractTransaction $transaction) : ResponseData
	{
		$request = new Request($this->getApiAuthentication(), $transaction->generate(), $this->config->useSandbox);
		$request->send();
		$response = new TransactionResponse($request->getResponse(), $transaction->getPaymentRequest());
		$response->process();
		return $response->getPaymentResponse();
	}

/* =============================================================
	AuthorizeNet
============================================================= */
	/**
	 * Return Authentication Object
	 * @return AnetAPI\MerchantAuthenticationType
	 */
	private function getApiAuthentication() : AnetAPI\MerchantAuthenticationType
	{
		$auth = new AnetAPI\MerchantAuthenticationType();
		$auth->setName($this->config->apiLogin);
		$auth->setTransactionKey($this->config->apiKey);
		return $auth;
	}
}
