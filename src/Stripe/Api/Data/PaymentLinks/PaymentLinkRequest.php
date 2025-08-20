<?php namespace Dpay\Stripe\Api\Data\PaymentLinks;
// Lib
use Dpay\Stripe\Api\Data\Data;


/**
 * PaymentLinkRequest
 * 
 * Data Container for Creating Payment Link
 * 
 * @property string    $id
 * @property LineItems $items
 * @property Metadata  $metadata
 * @property array     $paymentMethodTypes
 */
class PaymentLinkRequest extends Data {
    public function __construct() {
        $this->id    = '';
        $this->items = new LineItems();
        $this->metadata   = new Metadata();
        $this->paymentMethodTypes = [];
    }

    /**
     * Return Stripe Request
     * @return array{line_items: array, metadata: array, payment_method_types: array}
     */
    public function toApiArray() : array
    {
        $data = [
            'line_items' => $this->items->toArray(),
            'metadata'   => $this->metadata->getArray(),
            'payment_method_types' => $this->paymentMethodTypes,
        ];
        return $data;
    }

    /**
     * Return Stripe Request
     * @return array{line_items: array, metadata: array, payment_method_types: array}
     */
    public function requestArray() : array
    {
        $data = [
            'line_items' => $this->items->toArray(),
            'metadata'   => $this->metadata->getArray(),
            'payment_method_types' => $this->paymentMethodTypes,
        ];
        return $data;
    }
}