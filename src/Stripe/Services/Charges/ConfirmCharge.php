<?php namespace Dpay\Stripe\Services\Charges;

// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\ConfirmChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\Stripe\Endpoints;

/**
 * Service to Confirm CreditCard Charge using Stripe API
 * NOTE: uses PaymentIntents API
 *
 * @property string          $id          Generated Credit Charge ID
 * @property DpayCharge      $dpayCharge  Charge Data
 * @property StripeCharge    $sCharge     Stripe API Charge
 */
class ConfirmCharge extends AbstractCrudCharge implements ConfirmChargeInterface
{
    public const ACTION = 'confirm pre-authorized';

    #[\Override]
    public StripeCharge $sCharge;
    #[\Override]
    protected DpayCharge $dpayCharge;

    /* =============================================================
        Interface Contracts
    ============================================================= */
    /**
     * Return Response data as Dpay Credit Charge
     */
    public function getDpayChargeResponseData(): DpayCharge
    {
        $data = parent::getDpayChargeResponseData();
        $data->action = 'confirm';
        return $data;
    }

    /* =============================================================
        Internal Processing
    ============================================================= */
    /**
     * Create Stripe Customer
     */
    protected function processCharge(StripeCharge $data): StripeCharge|false
    {
        $stripeCharge = Endpoints\Charges::confirm($data);

        if (empty($stripeCharge->id) === false) {
            return $stripeCharge;
        }
        $this->errorMsg = Endpoints\Charges::$errorMsg;
        return false;
    }
}
