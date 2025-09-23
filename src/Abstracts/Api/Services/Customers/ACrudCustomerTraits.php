<?php namespace Dpay\Abstracts\Api\Services\Customers;
// Dpay
use Dpay\Data\Customer as DpayCustomer;

trait ACrudCustomerTraits {
/* =============================================================
	Inits
============================================================= */
	/**
	 * Init Dpay Customer
	 * @return bool
	 */
	protected function initDpayCustomer() : bool
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
	 * @param  DpayCustomer $customer
	 * @return void
	 */
	public function setDpayCustomer(DpayCustomer $dpayCustomer) : void
	{
		$this->dpayCustomer = $dpayCustomer;
	}

	/**
	 * Return Dpay Customer
	 * @return DpayCustomer
	 */
	public function getDpayCustomer() : DpayCustomer
	{
		return $this->dpayCustomer;
	}

	/**
	 * Set API ID
	 * @param  string $id  ID / Slug for API ID
	 * @return void
	 */
	public function setId($id) : void
	{
		$this->id = $id;
	}

	/**
	 * Return API Customer ID
	 * @return string
	 */
	public function getId() : string
	{
		return $this->id;
	}
}