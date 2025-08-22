<?php namespace Dpay\PayTrace\Transactions;
// Dplus Payments Model
use Payment;

/**
 * Generates Request Data needed for Transaction
 * 
 * @property Payment $paymentRequest
 */
abstract class AbstractTransaction {
	const ENDPOINT = '';
	protected Payment $paymentRequest;

	public function __construct(Payment $request) {
		$this->paymentRequest = $request;
	}

/* =============================================================
	Getters, Setters
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
	API Data builders
============================================================= */
	/**
	 * Return data needed for this Transaction Request
	 * @return array
	 */
	abstract public function generate() : array;
}
