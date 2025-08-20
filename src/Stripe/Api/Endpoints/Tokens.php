<?php namespace Dpay\Stripe\Api\Endpoints;
// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\Token as StripeToken;
// Lib
use Dpay\Stripe\ApiClient;
use Dpay\Stripe\Api\Data\CreditCards\CreditCardRequest as CardRequest;

/**
 * Tokens
 * Wrapper for Stripe API to interface with the Tokens Endpoint
 */
class Tokens extends AbstractEndpoint {
	public static string $errorMsg;

/* =============================================================
	Create
============================================================= */
	 /**
	  * Create Token for Credit Card
	  * @param  CardRequest $card
	  * @return StripeToken
	  */
	public static function createCard(CardRequest $rqst) : StripeToken
	{
		$stripe = ApiClient::instance();
		$data   = $rqst->toApiArray();

		try {
			$token = $stripe->tokens->create(['card' => $data]);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripeToken();
		} 
		return $token;
	}
}