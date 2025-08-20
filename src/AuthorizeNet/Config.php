<?php namespace Dpay\AuthorizeNet;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\Data;
use Pauldro\Minicli\v2\Util\EnvVarsReader as EnvVars;

/**
 * Config
 * Container for Stripe API Config Data
 * 
 * @property string $apiLogin          Api Login
 * @property string $apiKey            API Secret Key
 * @property bool   $useSandbox        Use API Sandbox?
 */
class Config extends Data {
	private static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public static function instance() : self
	{
		if (empty(self::$instance) === false) {
			return self::$instance;
		}
		self::$instance = new self();
		self::$instance->init();
		return self::$instance;
	}
	
	public function __construct() {
		$this->apiLogin = '';
		$this->apiKey   = '';
		$this->useSandbox = false;
	}

	/**
	 * Parse Field Values from $_ENV
	 * @return void
	 */
	public function init() : void 
	{
		$this->apiLogin   = EnvVars::get('AUTHORIZENET.API.LOGIN');
		$this->apiKey     = EnvVars::get('AUTHORIZENET.API.TRANSACTIONKEY');
		$this->useSandbox = EnvVars::getBool('AUTHORIZENET.API.USESANDBOX');
		return;
	}
}