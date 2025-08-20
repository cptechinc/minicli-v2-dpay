<?php namespace Dpay\AuthorizeNet;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\Data;

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
		$this->apiLogin = $_ENV["AUTHORIZENET.API.LOGIN"];
		$this->apiKey   = $_ENV["AUTHORIZENET.API.KEY"];
		if (array_key_exists('AUTHORIZENET.API.USESANDBOX', $_ENV) === false) {
			$this->useSandbox = false;
			return;
		}
		$this->useSandbox = $_ENV["AUTHORIZENET.API.USESANDBOX"] == 'true';
		return;
	}
}