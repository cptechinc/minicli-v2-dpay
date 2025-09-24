<?php namespace Dpay\Stripe\Endpoints;
// Lib
use Dpay\Stripe\ApiClient;

/**
 * AbstractEndpoint
 * Wrapper for Stripe API to interface with a given endpoint
 * 
 * @static string $errorMsg Error Message
 */
abstract class AbstractEndpoint {
	public static string $errorMsg;
	
	/**
	 * Return API client
	 * @return ApiClient
	 */
	public static function api() : ApiClient
	{
		return ApiClient::instance();
	}
}