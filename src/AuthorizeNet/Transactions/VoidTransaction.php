<?php namespace Dpay\AuthorizeNet\Transactions;
// AuthorizeNet Library
use net\authorize\api\contract\v1 as AnetAPI;

/**
 * Transactions\VoidTransaction
 *
 * Voids Card Transaction
 */
class VoidTransaction extends AbstractTransaction {
	const TYPE = 'voidTransaction';

	/**
	 * Set Transaction's Payment Data
	 * @param AnetAPI\TransactionRequestType $t
	 */
	protected function setTransactionTypePaymentData(AnetAPI\TransactionRequestType $t) : void
	{

	}
}
