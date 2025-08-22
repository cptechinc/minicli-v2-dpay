<?php namespace Dpay\PayTrace;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\Data;
use Pauldro\Minicli\v2\Util\EnvVarsReader as EnvVars;

/**
 * Config
 * Container for PayTrace API Config Data
 * 
 * @property string $apiLogin          APILogin
 * @property string $apiPassword       API Secret Key
 * @property string $integratorID
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
		$this->apiLogin      = '';
		$this->apiPassword   = '';
		$this->integratorID  = '';
	}

	/**
	 * Parse Field Values from $_ENV
	 * @return void
	 */
	public function init() : void 
	{
		$this->apiLogin      = EnvVars::get('PAYTRACE.API.LOGIN');
		$this->apiPassword   = EnvVars::get('PAYTRACE.API.PASSWORD');
		$this->integratorID  = EnvVars::getBool('PAYTRACE.API.INTEGRATORID');
		return;
	}
}