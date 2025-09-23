<?php namespace Dpay\AuthorizeNet\Api\Services\Charges;
// AuthorizeNet Library
use net\authorize\api\contract\v1\TransactionRequestType as ANetTransactionRequest;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\PreAuthChargeInterface;
use Dpay\AuthorizeNet\Api\Services\Charges\Util\TransactionData;
use Dpay\Util\ChargeStatus;

class PreAuthCharge extends AbstractCrudCharge implements PreAuthChargeInterface {
	const ACTION = 'pre-authorize';
	const ANET_TRANSACTION_TYPE = 'authOnlyTransaction';

/* =============================================================
	Contracts
============================================================= */

	protected function createTransactionRequest() : ANetTransactionRequest
	{
		$rqst = parent::createTransactionRequest();
		$rqst->setAmount($this->dpayCharge->amount);
		$rqst->setOrder(TransactionData::orderType($this->dpayCharge));
		$rqst->setBillTo(TransactionData::customerAddressType($this->dpayCharge));
		$rqst->setCustomer(TransactionData::customerDataType($this->dpayCharge));
		$rqst->setPayment(TransactionData::paymentType($this->dpayCharge));
		return $rqst;
	}

	protected function getSuccessfulChargeStatus() : ChargeStatus
	{
		return ChargeStatus::RequiresCapture;
	}
}