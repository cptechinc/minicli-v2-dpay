<?php namespace Dpay\Stripe\Api\Endpoints;
// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentLink;
use Stripe\PaymentMethod;
// Lib
use Dpay\Stripe\ApiClient;
use Dpay\Stripe\Config;
use Dpay\Stripe\Api\Data\PaymentLinks\PaymentLinkRequest;
use Dpay\Stripe\Api\Data\PaymentLinks\LineItems as LineItemsList; 

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
	 /**
	  * Create PaymentLink
	  * @param  PaymentLinkRequest $rqst
	  * @return PaymentLink
	  */
	public static function create(PaymentLinkRequest $rqst) : PaymentLink
	{
		$stripe = ApiClient::instance();

		try {
			$link = $stripe->paymentLinks->create($rqst->requestArray());
		} catch(ApiErrorException $e) {
			self::$errorMsg = $e->getMessage();
			return new PaymentLink();
		}
		return $link;
	}

	 /**
	  * Create, Return PaymentLink
	  * @param  LineItemsList $items
	  * @return PaymentLink
	  */
	public static function createFromLineItemsList(LineItemsList $items) : PaymentLink
	{
		$stripe = ApiClient::instance();
		$data = ['line_items' => $items->toArray()];
		$paymentTypes = self::getEnvAllowedPaymentTypes();

		if (empty($paymentTypes) === false) {
			$data['payment_method_types'] = $paymentTypes;
		}

		try {
			$link = $stripe->paymentLinks->create($data);
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
	
/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return Array of Allowed Payment Type Codes
	 * @return array
	 */
	private static function getEnvAllowedPaymentTypes() : array
	{
		$config = Config::instance();
		$allowedTypes = $config->allowedPaymentTypes;

		if (empty($allowedTypes)) {
			return [];
		}
		$types = [];

		foreach ($allowedTypes as $allowedType) {
			if (array_key_exists($allowedType, self::PAYMENT_METHOD_TYPES) === false) {
				continue;
			}
			$types[] = self::PAYMENT_METHOD_TYPES[$allowedType];
		}
		return $types;
	}
}