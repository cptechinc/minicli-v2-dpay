<?php namespace Dpay\Stripe\Services\PaymentLinks;

// Pauldro Minicli
use Pauldro\Minicli\v2\Util\SimpleArray;
// Stripe API Library
use Stripe\LineItem as StripeLineItem;
use Stripe\PaymentLink as StripePaymentLink;
use Stripe\Price as StripePrice;
use Stripe\Product as StripeProduct;
// Dpay
use Dpay\Abstracts\Api\Services\PaymentLinks\CreatePaymentLinkInterface;
use Dpay\Data\PaymentLink as DpayPaymentLink;
use Dpay\Data\Order\Item as DpayOrderItem;
use Dpay\Stripe\Endpoints;
use Dpay\Stripe\Data\PaymentLinks\PaymentLinkRequest;
use Dpay\Stripe\Data\PaymentLinks\LineItems as LineItemsList;

/**
 * Service to Create Payment Link to Stripe API
 *
 * @property string 			$id               Generated Payment Link ID
 * @property string 			$url	          Generated Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  PaymentLink Data
 * @property StripePaymentLink  $sPaymentLink     Stripe Payment Link
 * @property string             $errorMsg
 */
class CreatePaymentLink extends AbstractCrudPaymentLink implements CreatePaymentLinkInterface
{
    public const ACTION = 'create';
    #[\Override]
    public StripePaymentLink $sPaymentLink;
    #[\Override]
    protected DpayPaymentLink $dpayPaymentLink;

    /* =============================================================
            Internal Processing
        ============================================================= */
    /**
     * Creates Stripe PaymentLink
     */
    protected function processPaymentLink(PaymentLinkRequest $data): StripePaymentLink
    {
        return $this->createPaymentLink($data);
    }

    /**
     * Return Payment Link Request
     */
    protected function generatePaymentLinkRequest(DpayPaymentLink $link): PaymentLinkRequest
    {
        $data = new PaymentLinkRequest();
        $data->items = $this->generateLineItemsList($link);
        $data->paymentMethodTypes = $this->getEnvAllowedPaymentTypes();
        $data->metadata->set('custid', $link->order->custid);
        $data->metadata->set('ordernbr', $link->order->ordernbr);
        $data->metadata->set('ordertype', $link->order->type);
        foreach ($link->metadata as $key => $value) {
            $data->metadata->set($key, $value);
        }
        $data->metadata->set('description', $link->description);
        return $data;
    }

    /**
     * Return LineItemsList
     * NOTE: will create Products, Prices
     * @property DpayPaymentLink $link
     */
    protected function generateLineItemsList(DpayPaymentLink $link): LineItemsList
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
     */
    protected function getOrCreateStripeProduct(DpayOrderItem $item): StripeProduct
    {
        $product = Endpoints\Products::fetch($item->itemid());
        if (empty($product->id) === false) {
            return $product;
        }
        $product = new StripeProduct($item->itemid());
        $product->name = $item->description;

        if (empty($item->description)) {
            if ($item->isLinetypeBatch()) {
                $product->name = "Payment #: $item->ordernbr";
            }
            if ($item->isLinetypeInvoice()) {
                $product->name = "Invoice #: $item->ordernbr";
            }
        }
        return Endpoints\Products::create($product);
    }

    /**
     * Create Stripe Price
     */
    protected function createStripePrice(DpayOrderItem $item, StripeProduct $product): StripePrice
    {
        $price = new StripePrice();
        $price->unit_amount_decimal = $item->price * 100;
        $price->product = $product->id;
        $price->currency = 'usd';
        return Endpoints\Prices::create($price);
    }

    /**
     * Return new Stripe Line Item
     */
    protected function newStripeLineItem(DpayOrderItem $item, StripeProduct $product, StripePrice $price): StripeLineItem
    {
        $line = new StripeLineItem();
        $line->price = $price->id;
        $line->quantity = $item->qty;
        return $line;
    }

    /**
     * Create Payment Link
     */
    protected function createPaymentLink(PaymentLinkRequest $rqst): StripePaymentLink
    {
        $link = Endpoints\PaymentLinks::create($rqst);

        if (empty($link->id) === false) {
            return $link;
        }
        $this->errorMsg = Endpoints\PaymentLinks::$errorMsg;
        return $link;
    }
}
