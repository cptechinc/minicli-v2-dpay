<?php namespace Dpay\Abstracts\Api;

/**
 * AbstractServiceInterface
 * Interface for API Services
 */
interface AbstractServiceInterface {

/* =============================================================
	Contract functions
============================================================= */
	/**
	 * Return API client
	 * @return ApiClientInterface
	 */
	public function api() : ApiClientInterface;
	
	/**
	 * Return if connection to API was made
	 * @return bool
	 */
	public function connect() : bool;

	/**
	 * Process Request
	 * @return bool
	 */
	public function process() : bool;
}