<?php namespace Dpay\Stripe\Endpoints;
// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\Customer;
// Lib
use Dpay\Stripe\ApiClient;

/**
 * Customers
 * Wrapper for Stripe API to interface with the Customers Endpoint
 */
class Customers extends AbstractEndpoint {
	public static string $errorMsg;

/* =============================================================
	Create
============================================================= */
	 /**
	  * Create, Return Customer
	  * @param  Customer $customer
	  * @return Customer
	  */
	public static function create(Customer $customer) : Customer
	{
		$stripe = ApiClient::instance();

		try {
			$customer = $stripe->customers->create($customer->toArray());
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new Customer('');
		}
		return $customer;
	}

/* =============================================================
	Read
============================================================= */
	/**
	 * Return Customer
	 * @param  string $id
	 * @return Customer
	 */
	public static function fetchById($id) : Customer
	{
		$stripe = ApiClient::instance();

		try {
			$customer = $stripe->customers->retrieve($id);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new Customer();
		}
		return $customer;
	}

/* =============================================================
	Update
============================================================= */
	 /**
	  * Update Customer
	  * @param  Customer $customer
	  * @return Customer
	  */
	public static function update(Customer $customer) : Customer
	{
		$stripe = ApiClient::instance();
		$data = $customer->toArray();
		unset($data['id']);
	
		try {
			$customer = $stripe->customers->update($customer->id, $data);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new Customer('');
		}
		return $customer;
	}

/* =============================================================
	Delete
============================================================= */
	/**
	 * Return Customer
	 * @param  string $id
	 * @return Customer
	 */
	public static function deleteById($id) : Customer
	{
		$stripe = ApiClient::instance();

		try {
			$customer = $stripe->customers->delete($id);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new Customer();
		}
		return $customer;
	}
}