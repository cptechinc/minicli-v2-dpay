<?php namespace Dpay\PayTrace\Services\Charges;

// Dpay
use Dpay\Data\Charge as DpayCharge;
use Dpay\PayTrace\Config;
use Dpay\Util\ChargeStatus;

class RefundCharge extends AbstractCrudCharge
{
    public const ACTION = 'refund';
    public const API_SUCCESS_RESPONSE_CODES = [106];
    public const ENDPOINT = 'sale/keyed';

    /* =============================================================
        Interface Contracts
    ============================================================= */
    protected function getSuccessfulChargeStatus(): ChargeStatus
    {
        return ChargeStatus::Refunded;
    }

    /* =============================================================
        Contracts
    ============================================================= */
    /**
     * Generate Credit Charge Request Data
     */
    protected function generateChargeRequest(DpayCharge $charge): array
    {
        return [
            'integrator_id'   => Config::instance()->integratorID,
            'invoice_id'      => $charge->ordernbr,
            'transaction_id'  => $charge->transactionid,
            'amount'          => $charge->amount,
            'billing_address' => [
                'name'           => $charge->card->name,
                'street_address' => $charge->card->address1,
                'city'           => '',
                'state'          => '',
                'zip'            => $charge->card->zipcode
            ]
        ];
    }
}
