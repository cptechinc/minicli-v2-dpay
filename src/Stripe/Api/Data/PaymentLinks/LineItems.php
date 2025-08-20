<?php namespace Dpay\Stripe\Api\Data\PaymentLinks;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\SimpleArray;
// Stripe
use Stripe\LineItem as StripeLineItem;

/**
 * LineItems
 * 
 * List Container for StripeLineItems
 * 
 * @method DataArray  add(StripeLineItem $item) Add an item to the end of the list
 */
class LineItems extends SimpleArray {
	/**
	 * Return new/blank item
	 * @return StripeLineItem
	 */
	public function makeBlankItem() : StripeLineItem
    {
		return new StripeLineItem();
	}

    /**
     * Return list data as array
     * @return array[]
     */
	public function toArray() : array 
    {
        $data = [];

        foreach ($this as $item) {
            /** @var StripeLineItem $item */
            $data[] = $item->toArray();
        }
        return $data;
    }
}