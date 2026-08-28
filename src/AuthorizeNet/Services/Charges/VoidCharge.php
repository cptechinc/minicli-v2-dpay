<?php namespace Dpay\AuthorizeNet\Services\Charges;

// AuthorizeNet Library
use net\authorize\api\contract\v1\TransactionRequestType as ANetTransactionRequest;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\VoidChargeInterface;
use Dpay\AuthorizeNet\Services\Charges\Util\TransactionData;
use Dpay\Util\ChargeStatus;

class VoidCharge extends AbstractCrudCharge implements VoidChargeInterface
{
    public const ACTION = 'void';
    public const ANET_TRANSACTION_TYPE = 'voidTransaction';
    public const CHARGE_STATUS_ON_SUCCESS = 'canceled';

    /* =============================================================
        Contracts
    ============================================================= */
    protected function createTransactionRequest(): ANetTransactionRequest
    {
        $rqst = parent::createTransactionRequest();
        $rqst->setAmount($this->dpayCharge->amount);
        $rqst->setOrder(TransactionData::orderType($this->dpayCharge));
        $rqst->setBillTo(TransactionData::customerAddressType($this->dpayCharge));
        $rqst->setCustomer(TransactionData::customerDataType($this->dpayCharge));
        return $rqst;
    }

    protected function getSuccessfulChargeStatus(): ChargeStatus
    {
        return ChargeStatus::Voided;
    }
}
