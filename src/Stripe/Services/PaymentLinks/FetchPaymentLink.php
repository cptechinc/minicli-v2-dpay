<?php namespace Dpay\Stripe\Services\PaymentLinks;

// Stripe API Library
use Stripe\PaymentLink as StripePaymentLink;
// Dpay
use Dpay\Abstracts\Api\Services\PaymentLinks\FetchPaymentLinkInterface;
use Dpay\Stripe\Data\PaymentLinks\PaymentLinkRequest;
use Dpay\Stripe\Endpoints;
use Dpay\Data\PaymentLink as DpayPaymentLink;

/**
 * Service to fetch PaymentLink from Stripe API
 *
 * @property string 			$id               Generated Payment Link ID
 * @property string 			$url	          Generated Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  PaymentLink Data
 * @property StripePaymentLink  $sPaymentLink     Stripe Payment Link
 * @property string             $errorMsg
 */
class FetchPaymentLink extends AbstractCrudPaymentLink implements FetchPaymentLinkInterface
{
    public const ACTION = 'fetch';
    #[\Override]
    public StripePaymentLink $sPaymentLink;
    #[\Override]
    protected DpayPaymentLink $dpayPaymentLink;

    /* =============================================================
            Inits
        ============================================================= */
    /**
     * Init Dpay PaymentLink
     */
    protected function initDpayPaymentLink(): bool
    {
        $this->dpayPaymentLink = new DpayPaymentLink();
        $this->dpayPaymentLink->id = $this->id;
        return true;
    }

    /* =============================================================
           Internal Processing
       ============================================================= */
    /**
     * Return Stripe PaymentLink
     */
    protected function processPaymentLink(PaymentLinkRequest $data): StripePaymentLink
    {
        return Endpoints\PaymentLinks::fetchById($data->id);
    }
}
