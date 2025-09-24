<?php namespace Dpay\Stripe\Endpoints;
// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent as StripePaymentIntent;
// Lib
use Dpay\Stripe\ApiClient;

/**
 * Charges
 * Wrapper for Stripe API to interface with the PaymentIntents Endpoint
 */
class Charges extends AbstractEndpoint {
	public static string $errorMsg;

/* =============================================================
	Public Processing
============================================================= */
	/**
	 * Cancel Payment
	 * @param  StripePaymentIntent $rqst
	 * @return StripePaymentIntent
	 */
	public static function cancel(StripePaymentIntent $rqst) : StripePaymentIntent
	{
		$stripe = ApiClient::instance();
		
		try {
			$charge = $stripe->paymentIntents->cancel($rqst->id);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripePaymentIntent('');
		}
		return $charge;
	}

 	/**
	  * Capture Payment
	  * @param  StripePaymentIntent $card
	  * @return StripePaymentIntent
	  */
	  public static function capturepreauth(StripePaymentIntent $rqst) : StripePaymentIntent
	  {
		$stripe = ApiClient::instance();
		
		try {
			$charge = $stripe->paymentIntents->capture($rqst->id);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripePaymentIntent('');
		}
		return $charge;
	}

	/**
	  * Confirm Payment
	  * @param  StripePaymentIntent $card
	  * @return StripePaymentIntent
	  */
	  public static function confirm(StripePaymentIntent $rqst) : StripePaymentIntent
	  {
		$stripe = ApiClient::instance();
		
		try {
			$charge = $stripe->paymentIntents->confirm($rqst->id);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripePaymentIntent('');
		}
		return $charge;
	}

	/**
	  * Create Stripe Charge
	  * @param  StripePaymentIntent $card
	  * @return StripePaymentIntent
	  */
	  public static function create(StripePaymentIntent $rqst) : StripePaymentIntent
	  {
		$stripe = ApiClient::instance();
		
		try {
			$charge = $stripe->paymentIntents->create($rqst->toArray());
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripePaymentIntent('');
		}
		return $charge;
	}

	/**
	  * Fetch Stripe Payment Intent
	  * @param  string $id 
	  * @return StripePaymentIntent
	  */
	  public static function fetchById($id) : StripePaymentIntent 
	  {
		$stripe = ApiClient::instance();
		
		try {
			$charge = $stripe->paymentIntents->retrieve($id);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripePaymentIntent('');
		}
		return $charge;
	}

	 /**
	  * Create, Return Stripe Charge
	  * @param  StripePaymentIntent $card
	  * @return StripePaymentIntent
	  */
	public static function preauth(StripePaymentIntent $rqst) : StripePaymentIntent
	{
		$stripe = ApiClient::instance();
		
		try {
			$charge = $stripe->paymentIntents->create($rqst->toArray());
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripePaymentIntent('');
		}
		return $charge;
	}

	/**
	  * Update Stripe Charge
	  * @param  StripePaymentIntent $rqst
	  * @return StripePaymentIntent
	  */
	  public static function update(StripePaymentIntent $rqst) : StripePaymentIntent
	  {
		$stripe = ApiClient::instance();
		
		try {
			$data = $rqst->toArray();
			if (array_key_exists('id', $data)) {
				unset($data['id']);
			}
			$charge = $stripe->paymentIntents->update($rqst->id, $data);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripePaymentIntent('');
		}
		return $charge;
	}
	
/* =============================================================
	Supplemental
============================================================= */
}