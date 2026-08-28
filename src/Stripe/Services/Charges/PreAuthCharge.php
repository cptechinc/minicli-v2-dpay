<?php namespace Dpay\Stripe\Services\Charges;

// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\PreAuthChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\Stripe\Endpoints;
use Dpay\Stripe\Config;

/**
 * Service to pre-authorize CreditCard Charge using Stripe API
 * NOTE: uses PaymentIntents API
 *
 * @property string          $id          Generated Credit Charge ID
 * @property DpayCharge      $dpayCharge  Charge Data
 * @property StripeCharge    $sCharge     Stripe API Charge
 */
class PreAuthCharge extends AbstractCrudCharge implements PreAuthChargeInterface
{
    public const ACTION = 'pre-authorize';
    #[\Override]
    public StripeCharge $sCharge;
    #[\Override]
    protected DpayCharge $dpayCharge;

    /* =============================================================
        Internal Processing
    ============================================================= */
    /**
     * Generate Credit Charge Data
     */
    protected function generateChargeRequest(DpayCharge $charge): StripeCharge
    {
        if ($charge->has('transactionid')) { // skip sending charge id, because it will be generated
            $charge->remove('transactionid');
        }
        $config = Config::instance();
        $data = parent::generateChargeRequest($charge);
        $data->capture_method = 'manual';
        $data->automatic_payment_methods = [
            'enabled' => true,
            'allow_redirects' => 'never'
        ];
        if ($config->autoConfirmPreauths) {
            $data->confirm = true;
        }
        return $data;
    }

    /**
     * Create Stripe Customer
     */
    protected function processCharge(StripeCharge $data): StripeCharge|false
    {
        $stripeCharge = Endpoints\Charges::preauth($data);

        if (empty($stripeCharge->id) === false) {
            return $stripeCharge;
        }
        $this->errorMsg = Endpoints\Charges::$errorMsg;
        return false;
    }
}
