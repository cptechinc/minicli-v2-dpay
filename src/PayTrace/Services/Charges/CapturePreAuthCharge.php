<?php namespace Dpay\PayTrace\Services\Charges;

// Dpay
use Dpay\Abstracts\Api\Services\Charges\CapturePreAuthChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\PayTrace\Config;
use Dpay\Util\ChargeStatus;

class CapturePreAuthCharge extends AbstractCrudCharge implements CapturePreAuthChargeInterface
{
    public const ACTION = 'capture pre-authorized';
    public const API_SUCCESS_RESPONSE_CODES = [101];
    public const ENDPOINT = 'authorization/capture';

    /* =============================================================
        Interface Contracts
    ============================================================= */
    protected function getSuccessfulChargeStatus(): ChargeStatus
    {
        return ChargeStatus::Captured;
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
            'integrator_id'  => Config::instance()->integratorID,
            'transaction_id' => $charge->transactionid
        ];
    }
}
