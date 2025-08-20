<?php namespace Dpay\AuthorizeNet;
// AuthorizeNet Library
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\controller as AnetController;

/**
 * Request
 * 
 * @property AnetAPI\MerchantAuthenticationType       $authentication  API Credentials
 * @property AnetAPI\TransactionRequestType           $transaction     SDK Transaction Request Data
 * @property AnetAPI\CreateTransactionResponse|null   $response        API Response
 * @property bool                                     $useSandbox      Use Sandbox API?
 */
class Request {
	protected AnetAPI\MerchantAuthenticationType $authentication;
	protected AnetAPI\TransactionRequestType $transaction;
	protected AnetAPI\CreateTransactionResponse $response;
	protected bool $useSandbox = false;
	

	public function __construct(AnetAPI\MerchantAuthenticationType $auth, AnetAPI\TransactionRequestType $tran, $useSandbox = false) {
		$this->authentication = $auth;
		$this->transaction    = $tran;
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
	 * @return AnetAPI\CreateTransactionResponse
	 */
	public function getResponse() : AnetAPI\CreateTransactionResponse|null
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
		$api = new AnetController\CreateTransactionController($this->getCreateTransactionRequest());
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
	 * @return AnetAPI\CreateTransactionRequest
	 */
	protected function getCreateTransactionRequest() : AnetAPI\CreateTransactionRequest
	{
		$t = new AnetAPI\CreateTransactionRequest();
		$t->setMerchantAuthentication($this->authentication);
		$t->setRefId('ref' . time());
		$t->setTransactionRequest($this->transaction);
		return $t;
	}
}