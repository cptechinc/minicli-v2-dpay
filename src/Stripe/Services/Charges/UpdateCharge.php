<?php namespace Dpay\Stripe\Services\Charges;

// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
// Lib
use Dpay\Abstracts\Api\Services\Charges\UpdateChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\Stripe\Endpoints;

/**
 * Service to update CreditCard Charge using Stripe API
 * NOTE: uses PaymentIntents API
 *
 * @property string          $id          Generated Credit Charge ID
 * @property DpayCharge      $dpayCharge  Charge Data
 * @property StripeCharge    $sCharge     Stripe API Charge
 */
class UpdateCharge extends AbstractCrudCharge implements UpdateChargeInterface
{
    public const ACTION = 'update';
    #[\Override]
    public StripeCharge $sCharge;
    #[\Override]
    protected DpayCharge $dpayCharge;

    /* =============================================================
        Inits
    ============================================================= */
    /**
     * Fetch Stripe Charge, verify if status can be acted on
     */
    protected function initStripeCharge(): bool
    {
        $this->sCharge = Endpoints\Charges::fetchById($this->id);

        if (empty($this->sCharge) || empty($this->sCharge->id)) {
            $this->errorMsg = 'Charge not found';
            return false;
        }
        return true;
    }

    /* =============================================================
            Interface Contracts
        ============================================================= */
    /**
     * Process Request
     */
    public function process(): bool
    {
        if ($this->initDpayCharge() === false) {
            return false;
        }
        $this->id = $this->dpayCharge->transactionid;
        if ($this->initStripeCharge() === false) {
            return false;
        }
        return parent::process();
    }

    /* =============================================================
            Internal Processing
        ============================================================= */
    /**
     * Create Stripe Customer
     */
    protected function processCharge(StripeCharge $data): StripeCharge|false
    {
        $stripeCharge = Endpoints\Charges::update($data);

        if (empty($stripeCharge->id) === false) {
            return $stripeCharge;
        }
        $this->errorMsg = Endpoints\Charges::$errorMsg;
        return false;
    }
}
