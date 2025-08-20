<?php namespace Dpay\AuthorizeNet\Transactions;
// AuthorizeNet Library
use net\authorize\api\contract\v1\TransactionRequestType as TransactionRequest;
// Dplus Payments Model
use Payment;
// Dpay
use Dpay\AuthorizeNet\TransactionData;

/**
 * AbstractTransaction
 * Generates AuthorizeNet SDK TransactionRequest Objects from Payment Request Data
 * 
 * @property Payment $payment  Payment Request
 */
abstract class AbstractTransaction {
	const TYPE = '';

	protected $paymentRequest;

	public function __construct(Payment $request) {
		$this->paymentRequest = $request;
	}

/* =============================================================
	1. Getters, Setters
============================================================= */
	/**
	 * Return Payment Request
	 * @return Payment
	 */
	public function getPaymentRequest() : Payment
	{
		return $this->paymentRequest;
	}

/* =============================================================
	2. AuthorizeNet SDK Objects
============================================================= */
	/**
	* Return AuthorizeNet SDK Transaction Request
	* @return TransactionRequest
	*/
	public function generate() : TransactionRequest
	{
		$transaction = new TransactionRequest();
		$transaction->setTransactionType(static::TYPE);
		$transaction->setRetail(TransactionData::transRetailInfoType($this->paymentRequest));
		$transaction->addToTransactionSettings(TransactionData::settingType());
		$this->setTransactionPaymentData($transaction);

		if ($this->paymentRequest->getTransactionid()) {
			$transaction->setRefTransId($this->paymentRequest->getTransactionid());
		}
		return $transaction;
	}

/* =============================================================
	AuthorizeNet SDK Object Decorating
============================================================= */
	/**
	 * Set Transaction's Payment Data
	 * @param TransactionRequest $t
	 */
	protected function setTransactionPaymentData(TransactionRequest $t) : void
	{
		$t->setAmount($this->paymentRequest->getAmount());
		$t->setOrder(TransactionData::orderType($this->paymentRequest));
		$t->setPayment(TransactionData::paymentType($this->paymentRequest));
		$t->setBillTo(TransactionData::customerAddressType($this->paymentRequest));
		$t->setCustomer(TransactionData::customerDataType($this->paymentRequest));
	}
}
