<?php namespace Dpay\Stripe\Endpoints;
// Stripe SDK
use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\LineItem;
use Stripe\PaymentLink;
use Stripe\PaymentMethod;
// Lib
use Dpay\Stripe\ApiClient;
use Dpay\Stripe\Data\PaymentLinks\PaymentLinkRequest;

/**
 * PaymentLinks
 * Wrapper for Stripe API to interface with the PaymentLinks Endpoint
 */
class PaymentLinks extends AbstractEndpoint {
	const PAYMENT_METHOD_TYPES = [
		'ach'       => PaymentMethod::TYPE_US_BANK_ACCOUNT,
		'amazonpay' => PaymentMethod::TYPE_AMAZON_PAY,
		'card'      => PaymentMethod::TYPE_CARD,
		'cashapp'   => PaymentMethod::TYPE_CASHAPP,
		'mobile'    => PaymentMethod::TYPE_MOBILEPAY,
		'paypal'    => PaymentMethod::TYPE_PAYPAL,
	];

	public static string $errorMsg;

/* =============================================================
	Public Processing
============================================================= */
	public static function create(PaymentLinkRequest $rqst) : PaymentLink
	{
		$stripe = ApiClient::instance();

		try {
			$link = $stripe->paymentLinks->create($rqst->apiCreateArray());
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new PaymentLink();
		}
		return $link;
	}

	public static function update(PaymentLinkRequest $rqst) : PaymentLink
	{
		$stripe = ApiClient::instance();

		try {
			$link = $stripe->paymentLinks->update($rqst->id, $rqst->apiUpdateArray());
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new PaymentLink();
		}
		return $link;
	}

	 

	/**
	 * Return PaymentLink
	 * @param  string $id
	 * @return PaymentLink|false
	 */
	public static function fetchById($id) : PaymentLink
	{
		$stripe = ApiClient::instance();

		try {
			$link = $stripe->paymentLinks->retrieve($id);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new PaymentLink();
		}
		return $link;
	}

	/**
	 * Return Line Items in payment link
	 * @param string $id
	 * @return Collection<LineItem>
	 */
	public static function fetchLineItems(string $id) : Collection
	{
		$stripe = ApiClient::instance();

		try {
			$items = $stripe->paymentLinks->allLineItems($id);
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new Collection();
		}
		return $items;
	}
}