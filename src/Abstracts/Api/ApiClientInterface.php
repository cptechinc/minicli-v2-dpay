<?php namespace Dpay\Abstracts\Api;

/**
 * Interface for API clients
 */
interface ApiClientInterface {
	/**
	 * Return Instance
	 * @return static
	 */
	public static function instance() : static;

	/**
	 * Return if APi is able to be connected to
	 * @return bool
	 */
	public function connect() : bool;
}