<?php namespace Dpay\Stripe\Api\Endpoints;
// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\Refund as StripeRefund;
// Lib
use Dpay\Stripe\ApiClient;

/**
 * Refunds
 * Wrapper for Stripe API to interface with the Refunds Endpoint
 */
class Refunds extends AbstractEndpoint {
	public static string $errorMsg;

/* =============================================================
	Public Processing
============================================================= */
	 /**
	  * Create Stripe Refund
	  * @param  StripeRefund $card
	  * @return StripeRefund
	  */
	public static function create(StripeRefund $rqst) : StripeRefund {
		$stripe = ApiClient::instance();
		
		try {
			$refund = $stripe->refunds->create($rqst->toArray());
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripeRefund('');
		}
		return $refund;
	}
	
/* =============================================================
	Supplemental
============================================================= */
}