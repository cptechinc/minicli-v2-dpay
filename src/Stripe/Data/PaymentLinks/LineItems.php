<?php namespace Dpay\Stripe\Data\PaymentLinks;
// Pauldro
use Pauldro\UtilityBelt\Data\SimpleArray;
// Stripe
use Stripe\LineItem as StripeLineItem;

/**
 * List Container for StripeLineItems
 * 
 * @method LineItems  add(StripeLineItem $item) Add an item to the end of the list
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
    public function getArray() : array 
    {
        $data = [];

        foreach ($this as $item) {
            /** @var StripeLineItem $item */
            $data[] = $item->toArray();
        }
        return $data;
    }
}