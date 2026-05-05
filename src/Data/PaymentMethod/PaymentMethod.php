<?php namespace Dpay\Data\PaymentMethod;
// Dpay
use Dpay\Data\Data;

/**
 * Container for PaymentMethod Data
 */
class PaymentMethod extends Data {
    const TYPES = [
        'ach'       => 'ACH Bank',
        'amazonpay' => 'Amazon Pay',
        'card'      => 'Credit Card',
        'cashapp'   => 'Cash App',
        'mobile'    => 'Mobile Pay',
        'paypal'    => 'PayPal',
    ];
}