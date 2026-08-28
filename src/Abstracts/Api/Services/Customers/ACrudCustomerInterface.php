<?php namespace Dpay\Abstracts\Api\Services\Customers;

// Dpay
use Dpay\Abstracts\Api\AbstractServiceInterface;
use Dpay\Data\Customer as Customer;

/**
 * Template class for Customer CRUD operations
 *
 * @property string    $id            API Customer ID
 * @property Customer  $dpayCustomer  Customer Data
 * @property string    $errorMsg
 */
interface ACrudCustomerInterface extends AbstractServiceInterface
{
    /* =============================================================
            Setters
        ============================================================= */
    /**
     * Set Customer
     */
    public function setDpayCustomer(Customer $customer): void;

    /**
     * Set API ID
     * @param  string $id  ID / Slug for API ID
     */
    public function setId($id): void;

    /* =============================================================
            Getters
        ============================================================= */
    /**
     * Return Generated Customer ID
     */
    public function getId(): string;

    /**
     * Return if Error has occurred
     */
    public function hasError(): bool;

    /**
     * Return Response data as Dpay Customer
     */
    public function getDpayCustomerResponseData(): Customer;
}
