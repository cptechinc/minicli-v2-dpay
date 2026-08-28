<?php namespace Dpay\Abstracts\Api\Services\Customers;

// Dpay
use Dpay\Data\Customer as DpayCustomer;

trait ACrudCustomerTraits
{
    /* =============================================================
            Inits
        ============================================================= */
    /**
     * Init Dpay Customer
     */
    protected function initDpayCustomer(): bool
    {
        if (empty($this->dpayCustomer)) {
            $this->errorMsg = 'Customer not set';
            return false;
        }
        return true;
    }

    /* =============================================================
            Interface Contracts
        ============================================================= */
    /**
     * Set Dpay Customer
     */
    public function setDpayCustomer(DpayCustomer $customer): void
    {
        $this->dpayCustomer = $customer;
    }

    /**
     * Return Dpay Customer
     */
    public function getDpayCustomer(): DpayCustomer
    {
        return $this->dpayCustomer;
    }

    /**
     * Set API ID
     * @param  string $id  ID / Slug for API ID
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Return API Customer ID
     */
    public function getId(): string
    {
        return $this->id;
    }
}
