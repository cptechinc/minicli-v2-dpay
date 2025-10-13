<?php namespace Dpay\Stripe\Data\PaymentLinks;
// Lib
use Dpay\Stripe\Data\Data;


/**
 * PaymentLinkRequest
 * 
 * Data Container for Creating Payment Link
 * 
 * @property string    $id
 * @property LineItems $items
 * @property Metadata  $metadata
 * @property array     $paymentMethodTypes
 * @property string    $redirectUrl
 * @property string    $description
 * @property bool      $isActive
 */
class PaymentLinkRequest extends Data {
    public function __construct() {
        $this->id    = '';
        $this->items      = new LineItems();
        $this->metadata   = new Metadata();
        $this->paymentMethodTypes = [];
        $this->redirectUrl = '';
        $this->description = '';
        $this->isActive    = true;
    }

    /**
     * Return Stripe Request
     * @return array{line_items: array, metadata: array, payment_method_types: array}
     */
    public function apiArray() : array
    {
        $data = [
            'line_items' => $this->items->getArray(),
            'metadata'   => $this->metadata->getArray(),
            'payment_method_types' => $this->paymentMethodTypes,
            'payment_intent_data'  => []
        ];
        if ($this->redirectUrl) {
            $data['after_completion'] = [
                'type'     => 'redirect',
                'redirect' => ['url' => $this->redirectUrl]
            ];
        }
        if ($this->description) {
            $data['payment_intent_data']['statement_descriptor'] = $this->description;
        }
        $data['payment_intent_data']['metadata'] = $this->metadata->getArray();
        return $data;
    }

    /**
     * Return Stripe Request
     * @return array{line_items: array, metadata: array, payment_method_types: array}
     */
    public function apiCreateArray() : array
    {
        return $this->apiArray();
    }

    public function apiUpdateArray() : array
    {
        $data = $this->apiArray();
        $data['active'] = $this->isActive;

        if ($this->items->count() == 0) {
            unset($data['line_items']);
        }

        if ($this->metadata->count() == 0) {
            unset($data['metadata']);
        }

        if ($this->redirectUrl == '') {
            $data['after_completion'] = [
                'type' => 'hosted_confirmation'
            ];
        }
        return $data;
    }
}