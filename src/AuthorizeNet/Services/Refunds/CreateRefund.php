<?php namespace Dpay\AuthorizeNet\Services\Refunds;
// AuthorizeNet Library
use net\authorize\api\contract\v1\TransactionRequestType as ANetTransactionRequest;
// Dpay
use Dpay\Abstracts\Api\Services\Refunds\CreateRefundInterface;
use Dpay\AuthorizeNet\Services\Charges\Util\TransactionData;

class CreateRefund extends AbstractCrudRefund implements CreateRefundInterface {
    const ACTION = 'refund';
    const ANET_TRANSACTION_TYPE = 'refundTransaction';
    const REFUND_STATUS_ON_SUCCESS = 'refunded';

    protected function createTransactionRequest() : ANetTransactionRequest
	{
		$rqst = parent::createTransactionRequest();
		$rqst->setAmount($this->dpayRefund->charge->amount);
		$rqst->setOrder(TransactionData::orderType($this->dpayRefund->charge));
		$rqst->setBillTo(TransactionData::customerAddressType($this->dpayRefund->charge));
		$rqst->setCustomer(TransactionData::customerDataType($this->dpayRefund->charge));
		$rqst->setPayment(TransactionData::paymentType($this->dpayRefund->charge));
		return $rqst;
	}
}