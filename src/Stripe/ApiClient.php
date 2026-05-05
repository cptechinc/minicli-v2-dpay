<?php namespace Dpay\Stripe;
// Stripe API
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
// Lib
use Dpay\Abstracts\Api\ApiClientInterface;

/**
 * Extends Stripe API client
 */
class ApiClient extends StripeClient implements ApiClientInterface {
	private static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public static function instance() : static
	{
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
	public function connect() : bool {
		try {
			$this->products->all(['limit' => 1]);
		} catch (ApiErrorException $e) {
			return false;
		}
		return true;
	}
}