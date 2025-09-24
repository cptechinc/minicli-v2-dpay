<?php namespace Dpay\AuthorizeNet\Services\Charges;
// AuthorizeNet Library
use net\authorize\api\contract\v1\TransactionRequestType as ANetTransactionRequest;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\CapturePreAuthChargeInterface;
use Dpay\AuthorizeNet\Services\Charges\Util\TransactionData;
use Dpay\Util\ChargeStatus;

class CapturePreAuthCharge extends AbstractCrudCharge implements CapturePreAuthChargeInterface {
	const ACTION = 'capture pre-authorized';
	const ANET_TRANSACTION_TYPE = 'priorAuthCaptureTransaction';

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
		return ChargeStatus::Captured;
	}
}