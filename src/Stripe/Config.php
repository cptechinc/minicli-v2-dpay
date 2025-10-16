<?php namespace Dpay\Stripe;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\EnvVarsReader as EnvVars;
use Pauldro\Minicli\v2\Util\SimpleArray;
// Dpay
use Dpay\Abstracts\Api\AbstractApiConfig;
use Dpay\Data\PaymentMethod\PaymentMethod;
use Dpay\Stripe\Services\Events\Util\EventType;

/**
 * Container for Stripe API Config Data
 * 
 * @method static Config instance()
 * 
 * @property string       $secretKey            API Secret Key
 * @property SimpleArray  $allowedPaymentTypes  Allowed Payment Types (ach,amazonpay), note if blank, then use stripe defaults
 * @property bool         $useSandbox
 * @property bool         $autoConfirmPreauths  Confirm Pre-Auths?
 */
class Config extends AbstractApiConfig {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->secretKey = '';
		$this->allowedPaymentTypes = new SimpleArray();
		$this->useSandbox = false;
		$this->autoConfirmPreauths = false;
	}

	protected function init() : void
	{
		$this->secretKey  = EnvVars::get('STRIPE.API.SECRETKEY');
		$this->useSandbox = EnvVars::getBool('STRIPE.API.USESANDBOX');
		$this->autoConfirmPreauths = EnvVars::getBool('STRIPE.API.PREAUTHS.AUTOCONFIRM');
		$this->setAllowedPaymentTypesFromEnv();
		$this->setActionableEventsFromEnv();
	}
	
/* =============================================================
	Setters
============================================================= */
	/**
	 * Parse Payment Types from $_ENV
	 * @return bool
	 */
	private function setAllowedPaymentTypesFromEnv() : void
	{
		if (EnvVars::exists('STRIPE.ALLOWED.PAYMENT.TYPES') === false) {
			$this->allowedPaymentTypes = [];
			return;
		}
		$types = EnvVars::getArray('STRIPE.ALLOWED.PAYMENT.TYPES');

		foreach ($types as $type) {
			if (array_key_exists($type, PaymentMethod::TYPES) === false) {
				continue;	
			}
			$this->allowedPaymentTypes->set($type, $type);
		}
	}

	private function setActionableEventsFromEnv() : void
	{
		if (EnvVars::exists('STRIPE.EVENTS.ACTIONABLE') === false) {
			return;
		}
		$types = EnvVars::getArray('STRIPE.EVENTS.ACTIONABLE');

		foreach ($types as $type) {
			$eventType = EventType::find($type);

			if ($eventType === false) {
				continue;
			}
			$this->actionableEvents->set($type, $eventType);
		}
	}
}