<?php namespace Dpay\AuthorizeNet;
// AuthorizeNet Library
use net\authorize\api\contract\v1 as AnetAPI;
// Dplus Payments Model
use Payment;
// Dpay
use Dpay\Abstracts\AbstractGateway;
use Dpay\AuthorizeNet\Response as TransactionResponse;
use Dpay\Data\PaymentResponse as ResponseData;

/**
 * Gateway
 * 
 * Wrapper For making API calls to Authorize.Net
 * @property Config $config 
 */
class Gateway extends AbstractGateway {
	const ENV_REQUIRED = [
		'AUTHORIZENET.API.LOGIN',
		'AUTHORIZENET.API.TRANSACTIONKEY'
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
		// Void transaction if it's still pending
		if ($this->isTransactionPendingSettlement($paymentRequest->getTransId()) === false) {
			return $this->void($paymentRequest);
		}
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
		$request = new RequestChargeTransaction($this->getApiAuthentication(), $transaction->generate(), $this->config->useSandbox);
		$request->send();
		$response = new TransactionResponse($request->getResponse(), $transaction->getPaymentRequest());
		$response->process();
		return $response->getPaymentResponse();
	}

	/**
	 * Get Transaction data
	 * @param  string $id
	 * @return AnetAPI\TransactionDetailsType
	 */
	private function getTransactionDetails(string $id) : AnetAPI\TransactionDetailsType {
		$request = new RequestTransactionDetails($this->getApiAuthentication(), $id, $this->config->useSandbox);
		$request->send();
		return $request->getResponse()->getTransaction();
	}

	/**
	 * Return Authorize.net Transaction's status
	 * @param  string $id
	 * @return string
	 */
	private function getTransactionStatus(string $id) : string 
	{
		$transaction = $this->getTransactionDetails($id);
		return $transaction->getTransactionStatus();
	}

	/**
	 * Check if Transaction is Pending Settlement
	 * @param  string $id
	 * @return bool
	 */
	private function isTransactionPendingSettlement(string $id) : bool
	{
		$status = $this->getTransactionStatus($id);
		$notAvailable = [
			RequestTransactionDetails::TRANSACTION_STATUSES['pending-settlement']
		];

		if (in_array($status, $notAvailable)) {
			return false;
		}
		return true;
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
