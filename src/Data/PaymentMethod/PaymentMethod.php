<?php namespace Dpay\Data\PaymentMethod;
// Lib
use Dpay\Data\Data;

/**
 * PaymentMethod
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