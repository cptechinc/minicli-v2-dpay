<?php namespace Dpay\Stripe;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\Data;
use Pauldro\Minicli\v2\Util\EnvVarsReader as EnvVars;
// Lib
use Dpay\Data\PaymentMethod\PaymentMethod;

/**
 * Config
 * Container for Stripe API Config Data
 * 
 * @property string $secretKey            API Secret Key
 * @property array  $allowedPaymentTypes  Allowed Payment Types (ach,amazonpay), note if blank, then use stripe defaults
 * @property bool   $useSandbox
 */
class Config extends Data {
	private static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public static function instance() : Config
	{
		if (empty(self::$instance) === false) {
			return self::$instance;
		}
		self::$instance = new self();
		self::$instance->init();
		return self::$instance;
	}
	
	public function __construct() {
		$this->secretKey = '';
		$this->allowedPaymentTypes = [];
		$this->useSandbox = false;
	}

	/**
	 * Parse Field Values from $_ENV
	 * @return bool
	 */
	public function init() : bool
	{
		$this->secretKey  = EnvVars::get('STRIPE.SECRET.KEY');
		$this->useSandbox = EnvVars::getBool('STRIPE.USESANDBOX');
		$this->setAllowedPaymentTypesFromEnv();
		return true;
	}
	
/* =============================================================
	Setters
============================================================= */
	/**
	 * Parse Payment Types from $_ENV
	 * @return bool
	 */
	public function setAllowedPaymentTypesFromEnv() : bool 
	{
		if (EnvVars::exists('APP.ALLOWED.PAYMENT.TYPES') === false) {
			$this->allowedPaymentTypes = [];
			return true;
		}
		$types = EnvVars::getArray('APP.ALLOWED.PAYMENT.TYPES');
		$paymentTypes = [];

		foreach ($types as $type) {
			if (array_key_exists($type, PaymentMethod::TYPES) === false) {
				continue;	
			}
			$paymentTypes[] = $type;
		}
		$this->allowedPaymentTypes = $paymentTypes;
		return true;
	}
}