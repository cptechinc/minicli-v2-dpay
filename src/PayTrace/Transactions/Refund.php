<?php namespace Dpay\PayTrace\Transactions;
// Payments Library
use Dpay\PayTrace\TransactionData;

/**
 * Generates Request Data needed for refunding Credit Card Charge
 */
class Refund extends AbstractTransaction {
	const ENDPOINT = 'sale/keyed';

	public function generate() : array
	{
		$data = [
			"transaction_id"  => $this->paymentRequest->getTransId(),
			"amount"          => $this->paymentRequest->getAmount(),
			"csc"             => $this->paymentRequest->cvv(),
			"billing_address" => TransactionData::dataBillingAddress($this->paymentRequest)
		];
		return array_merge(TransactionData::default($this->paymentRequest), $data);
	}

}
