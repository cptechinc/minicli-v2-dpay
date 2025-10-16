<?php namespace Dpay\PayTrace;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\EnvVarsReader as EnvVars;
// Dpay
use Dpay\Abstracts\Api\AbstractApiConfig;

/**
 * Container for PayTrace API Config Data
 * 
 * @method static Config instance()
 * 
 * @property string $apiLogin          APILogin
 * @property string $apiPassword       API Secret Key
 * @property string $integratorID
 */
class Config extends AbstractApiConfig {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	
	public function __construct() {
		parent::__construct();
		$this->apiLogin      = '';
		$this->apiPassword   = '';
		$this->integratorID  = '';
	}

	protected function init() : void 
	{
		$this->apiLogin      = EnvVars::get('PAYTRACE.API.LOGIN');
		$this->apiPassword   = EnvVars::get('PAYTRACE.API.PASSWORD');
		$this->integratorID  = EnvVars::getBool('PAYTRACE.API.INTEGRATORID');
		return;
	}
}