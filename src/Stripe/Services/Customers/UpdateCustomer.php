<?php namespace Dpay\Stripe\Services\Customers;

// Stripe API Library
use Stripe\Customer as StripeCustomer;
// Dpay
use Dpay\Abstracts\Api\Services\Customers\UpdateCustomerInterface;
use Dpay\Data\Customer as DpayCustomer;
use Dpay\Stripe\Endpoints;

/**
 * Service to Update Customer using Stripe API
 *
 * @property string              $id            Updated Customer ID
 * @property DpayCustomer        $dpayCustomer  Customer Data
 * @property StripeCustomer      $sCustomer     Stripe API Customer
 */
class UpdateCustomer extends AbstractCrudCustomer implements UpdateCustomerInterface
{
    public const ACTION_DESCRIPTION = 'update';
    #[\Override]
    public StripeCustomer $sCustomer;
    #[\Override]
    protected DpayCustomer $dpayCustomer;

    /* =============================================================
            Interface Contracts
        ============================================================= */
    /* =============================================================
        Internal Processing
    ============================================================= */
    /**
     * Update Stripe Customer
     */
    protected function processCustomer(StripeCustomer $rqst): StripeCustomer
    {
        $sCustomer = Endpoints\Customers::update($rqst);

        if (empty($sCustomer->id) === false) {
            return $sCustomer;
        }
        $this->errorMsg = Endpoints\Customers::$errorMsg;
        return $sCustomer;
    }
}
