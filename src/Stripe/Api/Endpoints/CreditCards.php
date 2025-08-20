<?php namespace Dpay\Stripe\Api\Endpoints;
// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\Card as StripeCard;
use Stripe\Token as StripeToken;
// Lib
use Dpay\Stripe\ApiClient;
use Dpay\Stripe\Config;
use Dpay\Stripe\Api\Data\CreditCards\CreditCardRequest as CardRequest;

/**
 * CreditCards
 * Wrapper for Stripe API to interface with the CreditCards Endpoint
 * NOTE: there's no sdk functionality for fetching card data
 */
class CreditCards extends AbstractEndpoint {
	public static string $errorMsg;

/* =============================================================
	Create
============================================================= */
	 /**
	  * Create StripeCard
	  * @param  CardRequest $rqst
	  * @return StripeCard
	  */
	public static function create(CardRequest $rqst) : StripeCard
	{
		$stripe = ApiClient::instance();

		if (Config::instance()->useSandbox) {
			$token = new StripeToken('tok_visa');
		}

		// TODO: handle getting existing token
		if (Config::instance()->useSandbox === false ) {
			$token = Tokens::createCard($rqst);

			if (empty($token->id)) {
				self::$errorMsg = Tokens::$errorMsg;
				return new StripeCard('');
			}
		}

		try {
			$rqst = $stripe->customers->createSource($rqst->custid, ['source' => $token->id]);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripeCard('');
		}
		return $rqst;
	}

/* =============================================================
	Update
============================================================= */
	 /**
	  * Update StripeCard
	  * @param  CardRequest $rqst
	  * @return StripeCard
	  */
	public static function update(CardRequest $rqst) : StripeCard
	{
		$stripe = ApiClient::instance();
		$data   = $rqst->toApiArray();

		try {
			$rqst = $stripe->customers->updateSource($rqst->custid, $rqst->id, $data);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripeCard();
		}
		return $rqst;
	}

/* =============================================================
	Delete
============================================================= */
	/**
	 * Delete StripeCard
	 * @param  CardRequest $rqst
	 * @return StripeCard
	 */
	public static function delete(CardRequest $rqst) : StripeCard
	 {
		$stripe = ApiClient::instance();

		try {
			$rqst = $stripe->customers->deleteSource($rqst->custid, $rqst->id, []);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new StripeCard();
		}
		return $rqst;
	}
}