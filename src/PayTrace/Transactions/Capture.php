<?php namespace Dpay\PayTrace\Transactions;
// Payments Library
use Dpay\PayTrace\TransactionData;

/**
 * Generates Request Data needed for Capturing Credit Card Charge
 */
class Capture extends AbstractTransaction {
	const ENDPOINT = 'sale/keyed';

	public function generate() : array
	{
		return TransactionData::dataCreditCardNotPresent($this->paymentRequest);
	}
}
