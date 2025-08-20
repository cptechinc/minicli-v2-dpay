<?php namespace Dpay;
use Exception;
// Lib
use DPAY\Abstracts\AbstractGateway;

/**
 * Gateways
 * 
 * Returns Payments Service
 */
class Gateways {
	const GATEWAYS = [
		'authorizenet' => 'AuthorizeNet',
		'paytrace'     => 'PayTrace',
		'stripe'       => 'Stripe'
	];

	/**
	 * Return if Service Exists
	 * @param  string $key
	 * @return bool
	 */
	public static function doesServiceExist($key) {
		return array_key_exists($key, self::GATEWAYS);
	}

	/**
	 * Return instance of Service
	 * @param  string $key
	 * @return AbstractGateway
	 */
	public static function service($key) {
		if (self::doesServiceExist($key) === false) {
			throw new Exception("Service for $key not found");
		}
		$serviceName = self::GATEWAYS[$key];
		$service = __NAMESPACE__ . "\\$serviceName\\Gateway";

		if (class_exists($service) === false)  {
			throw new Exception("Service for $key not found");
		}
		return new $service();
	}
}
