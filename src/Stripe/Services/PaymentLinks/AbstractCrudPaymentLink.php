<?php namespace Dpay\Stripe\Services\PaymentLinks;
// Stripe SDK
use Stripe\LineItem as StripeLineItem;
use Stripe\PaymentLink as StripePaymentLink;
use Stripe\PaymentMethod as StripePaymentMethod;
// Dpay
use Dpay\Abstracts\Api\Services\PaymentLinks\ACrudPaymentLinkTraits;
use Dpay\Data\PaymentLink as DpayPaymentLink;
use Dpay\Stripe\Config;
use Dpay\Stripe\Endpoints;
use Dpay\Stripe\Data\PaymentLinks\PaymentLinkRequest; 
use Dpay\Stripe\Services\AbstractService;

/**
 * AbstractCrudPaymentLink
 * Template Service to Create / Update PaymentLink using Stripe API
 * 
 * @property string 			$id               Generated Payment Link ID
 * @property string 			$url	          Generated Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  PaymentLink Data
 * @property StripePaymentLink  $sPaymentLink     Stripe Payment Link
 * @property string             $errorMsg
 */
abstract class AbstractCrudPaymentLink extends AbstractService {
	use ACrudPaymentLinkTraits;

	const ACTION = 'update';
	const PAYMENT_METHOD_TYPES = [
		'ach'       => StripePaymentMethod::TYPE_US_BANK_ACCOUNT,
		'amazonpay' => StripePaymentMethod::TYPE_AMAZON_PAY,
		'card'      => StripePaymentMethod::TYPE_CARD,
		'cashapp'   => StripePaymentMethod::TYPE_CASHAPP,
		'mobile'    => StripePaymentMethod::TYPE_MOBILEPAY,
		'paypal'    => StripePaymentMethod::TYPE_PAYPAL
	];

	public string $id;
	public string $url;
	public StripePaymentLink $sPaymentLink;
	protected DpayPaymentLink $dpayPaymentLink;
	
/* =============================================================
	Inits @see ACrudPaymentLinkTraits
============================================================= */
	
	
/* =============================================================
	Interface Contracts @see ACrudPaymentLinkTraits
============================================================= */
	/**
	 * Process Request
	 * @return bool
	 */
	public function process() : bool
	{
		if ($this->initDpayPaymentLink() === false) {
			return false;
		}
		$rqst         = $this->generatePaymentLinkRequest($this->dpayPaymentLink);
		$sPaymentLink = $this->processPaymentLink($rqst);

		if (empty($sPaymentLink->id)) {
			if ($this->errorMsg) {
				return false;
			}
			$this->errorMsg = "Unable to " . static::ACTION . " PaymentLink for {$this->dpayPaymentLink->order->ordernbr}";
			return false;
		}
		$this->sPaymentLink = $sPaymentLink;
		$this->id	        = $sPaymentLink->id;
		$this->url          = $sPaymentLink->url;
		$this->dpayPaymentLink = $this->getDpayPaymentLinkResponseData();
		return true;
	}

	/**
	 * Return Response data as Dpay PaymentLink
	 * @return DpayPaymentLink
	 */
	public function getDpayPaymentLinkResponseData() : DpayPaymentLink
	{
		$link = $this->sPaymentLink;
		$metadata = $link->metadata;

		$data = new DpayPaymentLink();
		$data->id       = $link->id;
		$data->url      = $link->url;
		$data->isActive = $link->active;
		$data->order->custid   = $metadata->offsetExists('custid') ? $metadata->custid : '';
		$data->order->ordernbr = $metadata->offsetExists('ordernbr') ? $metadata->ordernbr : '';

		foreach ($metadata->toArray() as $key => $value) {
			$data->metadata->set($key, $value);
		}
		if ($metadata->offsetExists('description')) {
			$data->description = $metadata->description;
		}
		if ($link->id) {
			$items = Endpoints\PaymentLinks::fetchLineItems($link->id);

			foreach ($items as $item) {
				/** @var StripeLineItem $item */
				$orderitem = $data->order->items->new();
				$orderitem->itemid = $item->id;
				$orderitem->description = $item->description;
				$orderitem->qty = $item->quantity;
				$orderitem->price = $item->price->unit_amount / 100;
				$data->order->items->add($orderitem);
			}
		}
		return $data;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Generate PaymentLink Request Data
	 * @param  DpayPaymentLink $link
	 * @return PaymentLinkRequest
	 */
	protected function generatePaymentLinkRequest(DpayPaymentLink $link) : PaymentLinkRequest
	{
		$data = new PaymentLinkRequest();
		if ($link->id) {
			$data->id = $link->id;
		}
		return $data;
	}

    /**
     * Creates Stripe PaymentLink
     * @param  PaymentLinkRequest $data
     * @return StripePaymentLink
     */
	protected function processPaymentLink(PaymentLinkRequest $data) : StripePaymentLink 
	{
		return new StripePaymentLink();
	}

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return Array of Allowed Payment Type Codes
	 * @return array
	 */
	protected function getEnvAllowedPaymentTypes() : array
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