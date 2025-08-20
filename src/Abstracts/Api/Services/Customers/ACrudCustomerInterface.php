<?php namespace Dpay\Abstracts\Api\Services\Customers;
// Lib
use Dpay\Abstracts\Api\AbstractServiceInterface;
use Dpay\Data\Customer as Customer;

/**
 * ACrudCustomerInterface
 * Template class for Customer CRUD operations
 * 
 * @property string    $id            Generated Customer ID
 * @property Customer  $dpayCustomer  Customer Data
 * @property string    $errorMsg
 */
interface ACrudCustomerInterface extends AbstractServiceInterface {

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set Customer
	 * @param  Customer $customer
	 * @return void
	 */
	public function setDpayCustomer(Customer $customer) : void;

	/**
	 * Set API ID
	 * @param  string $id  ID / Slug for API ID
	 * @return void
	 */
	public function setId($id) : void;

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return Generated Customer ID
	 * @return string
	 */
	public function getId() : string;

	/**
	 * Return if Error has occurred
	 * @return bool
	 */
	public function hasError() : bool;

	/**
	 * Return Response data as Dpay Customer
	 * @return Customer
	 */
	public function getDpayCustomerResponseData() : Customer;
}