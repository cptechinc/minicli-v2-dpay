<?php namespace Dpay\Stripe\Api\Endpoints;
// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\Price;
// Lib
use Dpay\Stripe\ApiClient;

/**
 * Prices
 * Wrapper for Stripe API to interface with the prices Endpoint
 */
class Prices extends AbstractEndpoint {
	public static string $errorMsg;

	/**
	 * Return if Product Exists
	 * @param  string $id
	 * @return bool
	 */
	public static function exists($id) : bool
	{
		$stripe = ApiClient::instance();

		try {
			$stripe->products->retrieve($id, []);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return false;
		}
		return true;
	}

	/**
	 * Create Price
	 * @param  Price $price
	 * @return Price
	 */
	public static function create(Price $price) : Price
	{
		$stripe = ApiClient::instance();

		try {
			$result = $stripe->prices->create($price->toArray());
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new Price();
		}
		return $result;
	}
}