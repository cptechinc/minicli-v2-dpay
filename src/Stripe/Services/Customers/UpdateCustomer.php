<?php namespace Dpay\Stripe\Services\Customers;
// Stripe API Library
use Stripe\Customer as StripeCustomer;
// Lib
use Dpay\Abstracts\Api\Services\Customers\UpdateCustomerInterface;
use Dpay\Data\Customer as DpayCustomer;
use Dpay\Stripe\Endpoints;

/**
 * UpdateCustomer
 * Service to Update Customer using Stripe API
 * 
 * 
 * @property string              $id            Updated Customer ID 
 * @property DpayCustomer        $dpayCustomer  Customer Data
 * @property StripeCustomer      $sCustomer     Stripe API Customer
 */
class UpdateCustomer extends AbstractCrudCustomer implements UpdateCustomerInterface {
	const ACTION_DESCRIPTION = 'update';
	public StripeCustomer $sCustomer;
	protected DpayCustomer $dpayCustomer;

/* =============================================================
	Interface Contracts
============================================================= */

/* =============================================================
	Internal Processing
============================================================= */
	/**
     * Update Stripe Customer
     * @param  StripeCustomer $rqst
     * @return StripeCustomer
     */
	protected function processCustomer(StripeCustomer $rqst) : StripeCustomer {
		$sCustomer = Endpoints\Customers::update($rqst);

		if (empty($sCustomer->id) === false) {
			return $sCustomer;
		}
		$this->errorMsg = Endpoints\Customers::$errorMsg;
		return $sCustomer;
	}
}