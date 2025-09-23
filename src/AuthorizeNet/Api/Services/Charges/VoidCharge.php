<?php namespace Dpay\AuthorizeNet\Api\Services\Charges;
// AuthorizeNet Library
use net\authorize\api\contract\v1\TransactionRequestType as ANetTransactionRequest;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\VoidChargeInterface;
use Dpay\AuthorizeNet\Api\Services\Charges\Util\TransactionData;
use Dpay\Util\ChargeStatus;

class VoidCharge extends AbstractCrudCharge implements VoidChargeInterface {
	const ACTION = 'void';
	const ANET_TRANSACTION_TYPE = 'voidTransaction';
	const CHARGE_STATUS_ON_SUCCESS = 'canceled';

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
		return $rqst;
	}

	protected function getSuccessfulChargeStatus() : ChargeStatus
	{
		return ChargeStatus::Voided;
	}
}