<?php namespace Dpay\Stripe\Api\Endpoints;
// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\Product;
// Lib
use Dpay\Stripe\ApiClient;

/**
 * Products
 * Wrapper for Stripe API to interface with the products Endpoint
 */
class Products extends AbstractEndpoint {
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
	 * Return Product
	 * @param  string $id
	 * @return Product
	 */
	public static function fetch($id) : Product
	{
		$stripe = ApiClient::instance();

		try {
			$product = $stripe->products->retrieve($id, []);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new Product();
		}
		return $product;
	}

	/**
	 * Create Product
	 * @param  Product $data
	 * @return Product
	 */
	public static function create(Product $data) : Product
	{
		$stripe = ApiClient::instance();
		
		try {
			$product = $stripe->products->create($data->toArray());
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new Product();
		}
		return $product;
	}
}