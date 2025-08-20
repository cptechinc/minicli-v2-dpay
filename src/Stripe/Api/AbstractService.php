<?php namespace Dpay\Stripe\Api;
// Lib
use Dpay\Stripe\ApiClient;

/**
 * AbstractService
 * Template for API Services for Stripe
 * 
 * @property string $errorMsg Error Message
 */
abstract class AbstractService {
	const ENABLED = true;

	public string $errorMsg = '';

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