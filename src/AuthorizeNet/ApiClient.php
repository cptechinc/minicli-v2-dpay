<?php namespace Dpay\AuthorizeNet;
// AuthorizeNet Library
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
// Lib
use Dpay\Abstracts\Api\ApiClientInterface;
use Dpay\AuthorizeNet\Config;

class ApiClient implements ApiClientInterface {
    private static $instance;
	private Config $config;

/* =============================================================
	Constructors / Inits
============================================================= */
	/**
	 * Return Instance
	 * @return ApiClient
	 */
	public static function instance() : static {
		if (empty(self::$instance) === false) {
			return self::$instance;
		}
		self::$instance = new self();
		self::$instance->config = Config::instance();
		return self::$instance;
	}

/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Return if API is able to be connected to
	 * @return bool
	 */
	public function connect() : bool
	{
		$response = $this->fetchMerchantDetails();

		if ($response == null || $response->getMessages()->getResultCode() != "Ok") {
			return false;
		}
		return true;
	}

/* =============================================================
	API requests
============================================================= */
	public function fetchMerchantDetails() : AnetAPI\GetMerchantDetailsResponse
	{
		$rqst = new AnetAPI\GetMerchantDetailsRequest();
    	$rqst->setMerchantAuthentication($this->getApiAuthentication());

		$api = new AnetController\GetMerchantDetailsController($rqst);
		return $api->executeWithApiResponse($this->getApiUrl());
	}

	public function sendChargeTransaction(AnetAPI\TransactionRequestType $transaction) : AnetAPI\CreateTransactionResponse 
	{
		$rqst = new AnetAPI\CreateTransactionRequest();
		$rqst->setMerchantAuthentication($this->getApiAuthentication());
		$rqst->setRefId('ref' . time());
		$rqst->setTransactionRequest($transaction);

		$api = new AnetController\CreateTransactionController($rqst);
		return $api->executeWithApiResponse($this->getApiUrl());
	}

	public function fetchTransactionDetails(string $id) : AnetAPI\GetTransactionDetailsResponse
	{
		$rqst = new AnetAPI\GetTransactionDetailsRequest();
		$rqst->setMerchantAuthentication($this->getApiAuthentication());
		$rqst->setRefId('ref' . time());
		$rqst->setTransid($id);

		$api = new AnetController\GetTransactionDetailsController($rqst);
		return $api->executeWithApiResponse($this->getApiUrl());
	}

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return Authentication Object
	 * @return AnetAPI\MerchantAuthenticationType
	 */
	protected function getApiAuthentication() : AnetAPI\MerchantAuthenticationType
	{
		$auth = new AnetAPI\MerchantAuthenticationType();
		$auth->setName($this->config->apiLogin);
		$auth->setTransactionKey($this->config->apiKey);
		return $auth;
	}

	/**
	 * Return API URL Depending on Environment needed
	 * @return string
	 */
	protected function getApiUrl() : string
	{
		return $this->config->useSandbox ? ANetEnvironment::SANDBOX : ANetEnvironment::PRODUCTION;
	}

}