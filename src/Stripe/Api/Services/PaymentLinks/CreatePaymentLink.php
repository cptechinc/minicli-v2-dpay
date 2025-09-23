<?php namespace Dpay\Stripe\Api\Services\PaymentLinks;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\SimpleArray;
// Stripe API Library
use Stripe\LineItem as StripeLineItem;
use Stripe\PaymentLink as StripePaymentLink;
use Stripe\Price as StripePrice;
use Stripe\Product as StripeProduct;
// Lib
use Dpay\Abstracts\Api\Services\PaymentLinks\CreatePaymentLinkInterface;
use Dpay\Data\PaymentLink as DpayPaymentLink;
use Dpay\Data\Order\Item as DpayOrderItem;
use Dpay\Stripe\Api\Endpoints;
use Dpay\Stripe\Api\Data\PaymentLinks\PaymentLinkRequest; 
use Dpay\Stripe\Api\Data\PaymentLinks\LineItems as LineItemsList; 

/**
 * CreatePaymentLink
 * Service to Create Payment Link to Stripe API
 * 
 * @property string 			$id               Generated Payment Link ID
 * @property string 			$url	          Generated Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  PaymentLink Data
 * @property StripePaymentLink  $sPaymentLink     Stripe Payment Link
 * @property string             $errorMsg
 */
class CreatePaymentLink extends AbstractCrudPaymentLink implements CreatePaymentLinkInterface {
	const ACTION = 'create';
	public StripePaymentLink $sPaymentLink;
	protected DpayPaymentLink $dpayPaymentLink;

/* =============================================================
	Internal Processing
============================================================= */
	 /**
     * Creates Stripe PaymentLink
     * @param  PaymentLinkRequest $data
     * @return StripePaymentLink
     */
	protected function processPaymentLink(PaymentLinkRequest $data) : StripePaymentLink
	{
		return $this->createPaymentLink($data);
	}

	/**
	 * Return Payment Link Request
	 * @param  DpayPaymentLink $link
	 * @return PaymentLinkRequest
	 */
	protected function generatePaymentLinkRequest(DpayPaymentLink $link) : PaymentLinkRequest
	{
		$data = new PaymentLinkRequest();
		$data->items = $this->generateLineItemsList($link);
		$data->paymentMethodTypes = $this->getEnvAllowedPaymentTypes();
		$data->metadata->custid   = $link->order->custid;
		$data->metadata->ordernbr = $link->order->ordernbr;
		return $data;
	}

	/**
	 * Return LineItemsList
	 * NOTE: will create Products, Prices
	 * @property DpayPaymentLink $link
	 * @return   LineItemsList
	 */
	private function generateLineItemsList(DpayPaymentLink $link) : LineItemsList
	{
		$items    = new LineItemsList();
		$products = new SimpleArray();

		foreach ($link->order->items as $item) {
			$product = $products->get($item->itemid());

			if (empty($product)) {
				$product = $this->getOrCreateStripeProduct($item);
				$products->set($item->itemid(), $product);
			}
			$price	  = $this->createStripePrice($item, $product);
			$lineitem = $this->newStripeLineItem($item, $product, $price);
			$items->add($lineitem);
		}
		return $items;
	}

	/**
	 * Fetch / Create Stripe Product
	 * @param  DpayOrderItem $item
	 * @return StripeProduct
	 */
	private function getOrCreateStripeProduct(DpayOrderItem $item) : StripeProduct
	{
		$product = Endpoints\Products::fetch($item->itemid());
		if (empty($product->id) === false) {
			return $product;
		}
		$product = new StripeProduct($item->itemid());
		$product->name = $item->linetype == 'invoice' ? "Invoice #: $item->ordernbr" : $item->description;
		return Endpoints\Products::create($product);
	}

	/**
	 * Create Stripe Price
	 * @param  DpayOrderItem $item
	 * @param  StripeProduct $product
	 * @return StripePrice
	 */
	private function createStripePrice(DpayOrderItem $item, StripeProduct $product) : StripePrice
	{
		$price = new StripePrice();
		$price->unit_amount_decimal = $item->price * 100;
		$price->product = $product->id;
		$price->currency = 'usd';
		return Endpoints\Prices::create($price);
	}

	/**
	 * Return new Stripe Line Item
	 * @param  DpayOrderItem $item
	 * @param  StripeProduct $product
	 * @param  StripePrice $price
	 * @return StripeLineItem
	 */
	private function newStripeLineItem(DpayOrderItem $item, StripeProduct $product, StripePrice $price) : StripeLineItem
	{
		$line = new StripeLineItem();
		$line->price = $price->id;
		$line->quantity = $item->qty;
		return $line;
	}

	/**
	 * Create Payment Link
	 * @param  PaymentLinkRequest $rqst
	 * @return StripePaymentLink
	 */
	private function createPaymentLink(PaymentLinkRequest $rqst) : StripePaymentLink
	{
		$link = Endpoints\PaymentLinks::create($rqst);

		if (empty($link->id) === false) {
			return $link;
		}
		$this->errorMsg = Endpoints\PaymentLinks::$errorMsg;
		return $link;
	}
}