<?php namespace Dpay\AuthorizeNet;
// AuthorizeNet Library
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\controller as AnetController;

/**
 * RequestChargeTransaction
 * 
 * @property AnetAPI\MerchantAuthenticationType          $authentication  API Credentials
 * @property string                                      $transactionid   Transaction ID
 * @property bool                                        $useSandbox      Use Sandbox API?
 * @property AnetAPI\GetTransactionDetailsResponse|null  $response        API Response
 * 
 */
class RequestTransactionDetails {
	protected AnetAPI\MerchantAuthenticationType $authentication;
	protected string $transaction;
	protected AnetAPI\GetTransactionDetailsResponse $response;
	protected bool $useSandbox = false;
	

	public function __construct(AnetAPI\MerchantAuthenticationType $auth, string $transactionid, $useSandbox = false) {
		$this->authentication = $auth;
		$this->transactionid  = $transactionid;
		$this->useSandbox     = $useSandbox;
	}

/* =============================================================
	1. Getters, Setters
============================================================= */
	/**
	 * Set if Requests should be sent to Sandbox
	 * @param bool $useSandbox
	 */
	public function setUseSandbox($useSandbox = true) : void
	{
		$this->useSandbox = $useSandbox;
	}

	/**
	 * Return Response
	 * @return AnetAPI\GetTransactionDetailsResponse
	 */
	public function getResponse() : AnetAPI\GetTransactionDetailsResponse|null
	{
		return $this->response;
	}

/* =============================================================
	2. Public
============================================================= */	
	/**
	 * Send Transaction Request
	 * @return void
	 */
	public function send() : void
	{
		$api = new AnetController\GetTransactionDetailsController($this->getTransactionDetailsRequest());
		$this->response = $api->executeWithApiResponse($this->getApiUrl());
	}

/* =============================================================
	3. AuthorizeNet SDK
============================================================= */
	/**
	 * Return API URL Depending on Environment needed
	 * @return string
	 */
	protected function getApiUrl() : string
	{
		return $this->useSandbox ? ANetEnvironment::SANDBOX : ANetEnvironment::PRODUCTION;
	}

	/**
	 * Return Create Transaction SDK Object
	 * @return AnetAPI\GetTransactionDetailsRequest
	 */
	protected function getTransactionDetailsRequest() : AnetAPI\GetTransactionDetailsRequest
	{
		$t = new AnetAPI\GetTransactionDetailsRequest();
		$t->setMerchantAuthentication($this->authentication);
		$t->setRefId('ref' . time());
		$t->setTransid($this->transactionid);
		return $t;
	}
}