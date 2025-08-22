<?php namespace Dpay\PayTrace\Transactions;
// Payments Library
use Dpay\PayTrace\TransactionData;

/**
 * Generates Request Data needed for pre-authorizing Credit Card Charge
 */
class Preauthorize extends AbstractTransaction {
	const ENDPOINT = 'authorization/keyed';

	public function generate() : array
	{
		return TransactionData::dataCreditCardNotPresent($this->paymentRequest);
	}
}
