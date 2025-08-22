<?php namespace Dpay\PayTrace\Transactions;
// Payments Library
use Dpay\PayTrace\TransactionData;

/**
 * Generates Request Data needed for voiding Credit Card Charge
 */
class VoidTransaction extends AbstractTransaction {
	const ENDPOINT = 'void';

	public function generate() : array
	{
		return TransactionData::defaultWithTransactionid($this->paymentRequest);
	}
}
