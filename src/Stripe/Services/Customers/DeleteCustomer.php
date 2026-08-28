<?php namespace Dpay\Stripe\Services\Customers;

// Stripe API Library
use Stripe\Customer as StripeCustomer;
// Dpay
use Dpay\Abstracts\Api\Services\Customers\DeleteCustomerInterface;
use Dpay\Stripe\Endpoints;
use Dpay\Data\Customer as DpayCustomer;

/**
 * Service to Get Customer from Stripe API
 *
 * @property string            $id          Customer ID / URL
 * @property DpayCustomer      $dpayCustomer	Dpay Customer
 * @property StripeCustomer    $sCustomer
 */
class DeleteCustomer extends AbstractCrudCustomer implements DeleteCustomerInterface
{
    #[\Override]
    public StripeCustomer $sCustomer;
    #[\Override]
    protected DpayCustomer $dpayCustomer;

    /* =============================================================
            Inits
        ============================================================= */
    /**
     * Init Dpay Customer
     */
    protected function initDpayCustomer(): bool
    {
        if (empty($this->id)) {
            $this->errorMsg = 'Customer not set';
            return false;
        }
        $this->dpayCustomer = new DpayCustomer();
        $this->dpayCustomer->aid = $this->id;
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
        if ($this->initDpayCustomer() === false) {
            return false;
        }
        $sCustomer = Endpoints\Customers::fetchById($this->id);

        if (empty($sCustomer->id) || $sCustomer->offsetExists('deleted') === true) {
            $this->sCustomer = $sCustomer;
            return true;
        }

        $rqst = $this->generateCustomerRequest($this->dpayCustomer);
        $this->sCustomer = $this->processCustomer($rqst);

        if (empty($this->sCustomer) || empty($this->sCustomer->id)) {
            $this->errorMsg = 'Customer not deleted';
            return false;
        }
        $this->dpayCustomer = $this->getDpayCustomerResponseData();
        return true;
    }

    /* =============================================================
            Internal Processing
        ============================================================= */
    /**
     * Generate Customer Request
     */
    protected function generateCustomerRequest(DpayCustomer $customer): StripeCustomer
    {
        return new StripeCustomer($customer->aid);
    }

    /**
    * Delete Customer
    */
    protected function processCustomer(StripeCustomer $rqst): StripeCustomer
    {
        return Endpoints\Customers::deleteById($rqst->id);
    }
}
