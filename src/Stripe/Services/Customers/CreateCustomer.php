<?php namespace Dpay\Stripe\Services\Customers;
// Stripe API Library
use Stripe\Customer as StripeCustomer;
// Lib
use Dpay\Abstracts\Api\Services\Customers\CreateCustomerInterface;
use Dpay\Data\Customer as DpayCustomer;
use Dpay\Stripe\Endpoints;

/**
 * CreateCustomer
 * Service to Create Customer using Stripe API
 * 
 * @property string              $id            Generated Customer ID
 * @property DpayCustomer        $dpayCustomer  Customer Data
 * @property StripeCustomer      $sCustomer      Stripe API Customer
 */
class CreateCustomer extends AbstractCrudCustomer implements CreateCustomerInterface {
	const ACTION_DESCRIPTION = 'create';
	public StripeCustomer $sCustomer;
	protected DpayCustomer $dpayCustomer;

/* =============================================================
	Interface Contracts
============================================================= */

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Create Stripe Customer
	 * @param  StripeCustomer $rqst
	 * @return StripeCustomer
	 */
	protected function processCustomer(StripeCustomer $rqst) : StripeCustomer
	{
		$sCustomer = Endpoints\Customers::create($rqst);

		if (empty($sCustomer->id) === false) {
			return $sCustomer;
		}
		$this->errorMsg = Endpoints\Customers::$errorMsg;
		return $sCustomer;
	}
}