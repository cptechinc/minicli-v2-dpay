<?php namespace Dpay\AuthorizeNet;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\EnvVarsReader as EnvVars;
// Dpay
use Dpay\Abstracts\Api\AbstractApiConfig;

/**
 * Container for Authorize.net API Config Data
 * 
 * @method static Config instance()
 * 
 * @property string $apiLogin          Api Login
 * @property string $apiKey            API Secret Key
 * @property bool   $useSandbox        Use API Sandbox?
 */
class Config extends AbstractApiConfig {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->apiLogin = '';
		$this->apiKey   = '';
		$this->useSandbox = false;
	}

	protected function init() : void 
	{
		$this->apiLogin   = EnvVars::get('AUTHORIZENET.API.LOGIN');
		$this->apiKey     = EnvVars::get('AUTHORIZENET.API.TRANSACTIONKEY');
		$this->useSandbox = EnvVars::getBool('AUTHORIZENET.API.USESANDBOX');
	}
}