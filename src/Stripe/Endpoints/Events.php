<?php namespace Dpay\Stripe\Endpoints;
// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\Event;
// Lib
use Dpay\Stripe\ApiClient;

/**
 * Events
 * Wrapper for Stripe API to interface with the Events Endpoint
 */
class Events extends AbstractEndpoint {
	public static string $errorMsg;

/* =============================================================
	Read
============================================================= */
	/**
	 * Return Event
	 * @param  string $id
	 * @return Event
	 */
	public static function fetchById($id) : Event
	{
		$stripe = ApiClient::instance();

		try {
			$event = $stripe->events->retrieve($id);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new Event();
		}
		return $event;
	}
}