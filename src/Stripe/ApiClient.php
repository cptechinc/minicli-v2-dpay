<?php namespace Dpay\Stripe;
// Stripe API
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
// Lib
use Dpay\Abstracts\Api\ApiClientInterface;

/**
 * ApiClient
 * Extends Stripe API client
 */
class ApiClient extends StripeClient implements ApiClientInterface {
	private static $instance;

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
		$config = Config::instance();
		self::$instance = new self($config->secretKey);
		return self::$instance;
	}

/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Return if APi is able to be connected to
	 * @return bool
	 */
	public function connect() : bool {
		try {
			$this->products->all(['limit' => 1]);
		} catch (ApiErrorException $e) {
			return false;
		}
		return true;
	}
}