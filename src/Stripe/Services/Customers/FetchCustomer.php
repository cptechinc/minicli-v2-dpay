<?php namespace Dpay\Stripe\Services\Customers;
// Stripe API Library
use Stripe\Customer as StripeCustomer;
// Lib
use Dpay\Abstracts\Api\Services\Customers\FetchCustomerInterface;
use Dpay\Stripe\Endpoints;
use Dpay\Data\Customer as DpayCustomer;

/**
 * FetchCustomer
 * Service to Get Customer from Stripe API
 * 
 * @property string            $id          Customer ID / URL
 * @property DpayCustomer      $dpayCustomer	Dpay Customer
 * @property StripeCustomer    $sCustomer
 */
class FetchCustomer extends AbstractCrudCustomer implements FetchCustomerInterface {
	const ACTION_DESCRIPTION = 'fetch';
	public StripeCustomer $sCustomer;
	protected DpayCustomer $dpayCustomer;

/* =============================================================
	Inits
============================================================= */
	/**
	 * Init Dpay Customer
	 * @return bool
	 */
	protected function initDpayCustomer() : bool
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

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Generate Customer Request
	 * @param  DpayCustomer $customer
	 * @return StripeCustomer
	 */
	protected function generateCustomerRequest(DpayCustomer $customer) : StripeCustomer
	{
		return new StripeCustomer($customer->aid);
	}
	
	/**
	* Return Stripe Customer
	* @param  StripeCustomer $rqst
	* @return StripeCustomer
	*/
   protected function processCustomer(StripeCustomer $rqst) : StripeCustomer
   {
		return Endpoints\Customers::fetchById($rqst->id);
   }
}