<?php namespace Dpay\PayTrace\Transactions;
// Payments Library
use Dpay\PayTrace\TransactionData;

/**
 * Generates Request Data needed for Capturing pre-authorized Credit Card Charge
 */
class CapturePreauthorized extends AbstractTransaction {
	const ENDPOINT = 'authorization/capture';

	public function generate() : array
	{
		return TransactionData::defaultWithTransactionid($this->paymentRequest);
	}

}
