<?php namespace Dpay\AuthorizeNet;
// Authorize.Net SDK
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1\ANetApiRequestType; 
use net\authorize\api\contract\v1\ANetApiResponseType; 
use net\authorize\api\contract\v1\MerchantAuthenticationType; 
use net\authorize\api\controller\base\ApiOperationBase as AnetController;


/**
 * @property bool						 $useSandbox
 * @property MerchantAuthenticationType  $authentication
 * @property ANetApiRequestType 		 $request
 * @property ANetApiResponseType		 $response
 */
abstract class AbstractRequest {
	protected bool $useSandbox = false;
	protected MerchantAuthenticationType $authentication;
	protected ANetApiRequestType $request;
	protected ANetApiResponseType $response;

    protected function initSend() : void {
		$this->request = $this->createRequest();
	}

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set if Requests should be sent to Sandbox
	 * @param bool $useSandbox
	 */
	public function setUseSandbox($useSandbox = true) : void
	{
		$this->useSandbox = $useSandbox;
	}

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return Response
	 * @return ANetApiResponseType
	 */
	public function getResponse() : ANetApiResponseType|null
	{
		return $this->response;
	}

/* =============================================================
	Public
============================================================= */	
	/**
	 * Send Transaction Request
	 * @return void
	 */
	public function send() : void
	{
		$this->initSend();
		$api = $this->createRequestController();
		$this->response = $api->executeWithApiResponse($this->getApiUrl());
	}

/* =============================================================
	Authorize.Net SDK
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
     * Return Request to send to API
     * @return ANetApiRequestType
     */
	abstract protected function createRequest() : ANetApiRequestType;
    
    /**
     * Return Controller to Send Request
     * @return AnetController
     */
	abstract protected function createRequestController() : AnetController;
}