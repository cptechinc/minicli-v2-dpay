<?php namespace Dpay\Stripe\Api\Services\Customers;
// Stripe API Library
use Stripe\Customer as StripeCustomer;
// Lib
use Dpay\Data\Customer as DpayCustomer;
use Dpay\Stripe\Api\AbstractService;

/**
 * CreateCustomer
 * Service to Create Customer using Stripe API
 * 
 * @property string 			 $id			API Customer ID
 * @property DpayCustomer		 $dpayCustomer	Dpay Customer
 * @property StripeCustomer 	 $sCustomer 	Stripe API Customer
 */
abstract class AbstractCrudCustomer extends AbstractService {
	const ACTION_DESCRIPTION = 'create';

	protected string $id;
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
		$this->sCustomer = $this->processCustomer($rqst);

		if (empty($this->sCustomer) || empty($this->sCustomer->id)) {
			if ($this->errorMsg) {
				return false;
			}
			$this->errorMsg = "Unable to " . static::ACTION_DESCRIPTION . " Customer {$this->dpayCustomer->custid}";
			return false;
		}
		$this->id = $this->sCustomer->id;
		$this->dpayCustomer = $this->getDpayCustomerResponseData();
		return true;
	}

	/**
	 * Return Response data as Dpay Customer
	 * @return DpayCustomer
	 */
	public function getDpayCustomerResponseData() : DpayCustomer
	{
		$cust     = $this->sCustomer;
		$data = new DpayCustomer();
		$data->aid = $cust->id;
		$data->custname = $cust->offsetExists('address') ? $cust->name : '';
		$data->billtoaddress1 = $cust->offsetExists('address') ? $cust->address->line1 : '';
		$data->billtoaddress2 = $cust->offsetExists('address') ? $cust->address->line2 : '';
		$data->billtocity     = $cust->offsetExists('address') ? $cust->address->city : '';
		$data->billtostate    = $cust->offsetExists('address') ? $cust->address->state : '';
		$data->billtozipcode  = $cust->offsetExists('address') ? $cust->address->postal_code : '';
		$data->billtocountry  = $cust->offsetExists('address') ? $cust->address->country : '';

		if ($cust->offsetExists('metadata')) {
			$metadata = $cust->metadata;
			$data->custid   = $metadata->offsetExists('custid') ? $metadata->custid : '';
		}
		return $data;
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
		$sCustomer = $customer->aid ? new StripeCustomer($customer->aid) : new StripeCustomer();
		$sCustomer->name = $customer->custname;
		$sCustomer->metadata = ['custid' => $customer->custid];
		$sCustomer->address = [
			'line1' => $customer->billtoaddress1,
			'line2' => $customer->billtoaddress2,
			'city'  => $customer->billtocity,
			'state' => $customer->billtostate,
			'postal_code' => $customer->billtozipcode,
			'country'     => $customer->billtocountry,
		];
		return $sCustomer;
	}

    /**
     * Process Stripe Customer Request
     * @param  StripeCustomer $rqst
     * @return StripeCustomer|false
     */
	abstract protected function processCustomer(StripeCustomer $rqst) : StripeCustomer|false;
}