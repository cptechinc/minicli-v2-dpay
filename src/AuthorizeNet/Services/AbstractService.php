<?php namespace Dpay\AuthorizeNet\Services;
// Dpay
use Dpay\AuthorizeNet\ApiClient;
use Dpay\AuthorizeNet\Config;


/**
 * Template for API Services for Authorize.net
 * 
 * @property string $errorMsg Error Message
 */
abstract class AbstractService {
	public string $errorMsg = '';
	protected Config $config;

	public function __construct() {
		$this->config = Config::instance();
	}

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return if Error has occurred
	 * @return bool
	 */
	public function hasError() : bool
	{
		return empty($errorMsg) === false;
	}

	/**
	 * Return API client
	 * @return ApiClient
	 */
	public function api() : ApiClient
	{
		return ApiClient::instance();
	}
	
	/**
	 * Return if connection to API was made
	 * @return bool
	 */
	public function connect() : bool
	{
		return $this->api()->connect();
	}

/* =============================================================
	Contract functions
============================================================= */
	/**
	 * Process Request
	 * @return bool
	 */
	abstract public function process() : bool;
}