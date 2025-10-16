<?php namespace Dpay\Abstracts\Api;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\Data;
use Pauldro\Minicli\v2\Util\SimpleArray;

/**
 * @property SimpleArray $actionableEvents
 */
abstract class AbstractApiConfig extends Data {
    protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public static function instance() : static
	{
		if (empty(static::$instance) === false) {
			return static::$instance;
		}
		static::$instance = new static();
		static::$instance->init();
		return static::$instance;
	}

    public function __construct() {
        $this->actionableEvents = new SimpleArray();
    }

	protected function init() : void
	{

	}
}