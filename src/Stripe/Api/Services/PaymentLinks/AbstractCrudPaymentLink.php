<?php namespace Dpay\Stripe\Api\Services\PaymentLinks;
// Stripe API Library
use Stripe\PaymentLink as StripePaymentLink;
use Stripe\PaymentMethod as StripePaymentMethod;
// Lib
use Dpay\Stripe\Config;
use Dpay\Stripe\Api\AbstractService;
use Dpay\Stripe\Api\Data\PaymentLinks\PaymentLinkRequest; 
use Dpay\Data\PaymentLink as DpayPaymentLink;

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
	const ACTION_DESCRIPTION = 'update';
	const PAYMENT_METHOD_TYPES = [
		'ach'       => StripePaymentMethod::TYPE_US_BANK_ACCOUNT,
		'amazonpay' => StripePaymentMethod::TYPE_AMAZON_PAY,
		'card'      => StripePaymentMethod::TYPE_CARD,
		'cashapp'   => StripePaymentMethod::TYPE_CASHAPP,
		'mobile'    => StripePaymentMethod::TYPE_MOBILEPAY,
		'paypal'    => StripePaymentMethod::TYPE_PAYPAL,
	];
	public string $id;
	public string $url;
	public StripePaymentLink $sPaymentLink;
	protected DpayPaymentLink $dpayPaymentLink;
	
/* =============================================================
	Inits
============================================================= */
	/**
	 * Init Dpay PaymentLink
	 * @return bool
	 */
	protected function initDpayPaymentLink() {
		if (empty($this->dpayPaymentLink)) {
			$this->errorMsg = 'PaymentLink Data not set';
			return false;
		}
		return true;
	}
	
/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Set Dpay PaymentLink
	 * @param  DpayPaymentLink $link
	 * @return void
	 */
	public function setDpayPaymentLink(DpayPaymentLink $dpayPaymentLink) : void
	{
		$this->dpayPaymentLink = $dpayPaymentLink;
	}

	/**
	 * Return Dpay PaymentLink
	 * @return DpayPaymentLink
	 */
	public function getDpayPaymentLink() : DpayPaymentLink {
		return $this->dpayPaymentLink;
	}

	/**
	 * Set API ID
	 * @param  string $id
	 * @return void
	 */
	public function setId($id) : void {
		$this->id = $id;
	}

	/**
	 * Return API PaymentLink ID
	 * @return string
	 */
	public function getId() : string {
		return $this->id;
	}

	/**
	 * Return Payment Link URL
	 * @return string
	 */
	public function getUrl() : string {
		return $this->url;
	}

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
			$this->errorMsg = "Unable to " . static::ACTION_DESCRIPTION . " PaymentLink for {$this->dpayPaymentLink->order->ordernbr}";
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
	public function getDpayPaymentLinkResponseData() : DpayPaymentLink {
		$link = $this->sPaymentLink;
		$metadata = $link->metadata;

		$data = new DpayPaymentLink();
		$data->id       = $link->id;
		$data->url      = $link->url;
		$data->isActive = $link->active;
		$data->order->custid   = $metadata->offsetExists('custid') ? $metadata->custid : '';
		$data->order->ordernbr = $metadata->offsetExists('ordernbr') ? $metadata->ordernbr : '';
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
	protected static function getEnvAllowedPaymentTypes() : array
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