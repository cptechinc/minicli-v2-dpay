<?php namespace Dpay\Stripe\Api\Services\Customers;
// Stripe API Library
use Stripe\Customer as StripeCustomer;
// Lib
use Dpay\Abstracts\Api\Services\Customers\DeleteCustomerInterface;
use Dpay\Stripe\Api\Endpoints;
use Dpay\Data\Customer as DpayCustomer;

/**
 * DeleteCustomer
 * Service to Get Customer from Stripe API
 * 
 * @property string            $id          Customer ID / URL
 * @property DpayCustomer      $dpayCustomer	Dpay Customer
 * @property StripeCustomer    $sCustomer
 */
class DeleteCustomer extends AbstractCrudCustomer implements DeleteCustomerInterface {
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
	/**
	 * Process Request
	 * @return bool
	 */
	public function process() : bool
	{
		if ($this->initDpayCustomer() === false) {
			return false;
		}
		$rqst = $this->generateCustomerRequest($this->dpayCustomer);
		$this->sCustomer  = $this->processCustomer($rqst);

		if (empty($this->sCustomer) || empty($this->sCustomer->id)) {
			$this->errorMsg = 'Customer not delete';
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
	 * @param  DpayCustomer $customer
	 * @return StripeCustomer
	 */
	protected function generateCustomerRequest(DpayCustomer $customer) : StripeCustomer
	{
		return new StripeCustomer($customer->aid);
	}

	/**
	* Delete Customer
	* @param  StripeCustomer $rqst
	* @return StripeCustomer
	*/
   protected function processCustomer(StripeCustomer $rqst) : StripeCustomer
   {
		return Endpoints\Customers::deleteById($rqst->id);
   }
}