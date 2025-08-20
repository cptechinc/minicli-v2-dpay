<?php namespace Dpay\Stripe\Services;
// Stripe
use Stripe\Customer as StripeCustomer;
// Dplus Models
use Customer as DplusCustomer;
// Dplus Payments Model
use Payment;
// DTOs
use Dpay\Data\Charge as ChargeDTO;
use Dpay\Data\Customer as CustomerDTO;
use Dpay\Data\PaymentResponse as Response;
// Dplus Database
use Lib\Dplus\Db\Tables\Customers as DplusCustomersTable;
// App Database
use Lib\Logs\Database\Customers as ApiCustomersTable;
// Stripe Services
use Dpay\Stripe\Api\Services\Customers as CustomerServices;

/**
 * CustomersService
 * Service for charge customer setup
 * 
 * @property string    $errorMsg
 * @property Response  $lastResponse
 * @property ChargeDTO $charge
 */
class CustomersService extends AbstractService {
	public string $errorMsg;
	public Response $lastResponse;
	protected ChargeDTO $charge;
/* =============================================================
	Public
============================================================= */
	public function process() : bool
	{
		$this->setChargeAcustidFromLogTable();

		if (empty($this->charge->acustid) === false) {
			return true;
		}

		if ($this->setupCustomer() === false) {
			return false;
		}
		return true;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Create Customer
	 * @param  Payment    $rqst
	 * @param  ChargeDTO  $charge
	 * @return bool
	 */
	private function setupCustomer() : bool
	{
		$customer = $this->generateCustomerDTOFromDplusDb($this->charge->custid);

		if (empty($customer)) {
			$this->lastResponse = $this->respondCustomerNotFound($this->rqst);
			return false;
		}
		if ($this->createCustomer($customer) === false) {
			$this->lastResponse = $this->respondCustomerCreateFailed($this->rqst);
			return false;
		}
		$this->charge->acustid = $customer->aid;
		$this->charge->card->acustid = $customer->aid;
		return true;
	}

/* =============================================================
	API Services
============================================================= */
	/**
	 * Create Customer
	 * @param  CustomerDTO $customer
	 * @return bool
	 */
	private function createCustomer(CustomerDTO $customer) : bool
	{
		$SERVICE = new CustomerServices\CreateCustomer();
		$SERVICE->setDpayCustomer($customer);
		$SERVICE->process();

		/** @var StripeCustomer */
		$sCustomer = $SERVICE->sCustomer;

		if (empty($sCustomer->id)) {
			$this->errorMsg = $SERVICE->errorMsg;
			return false;
		}
		$customer->aid = $sCustomer->id;
		$this->insertCustomerLogDb($customer);
		return true;
	}

/* =============================================================
	App Database
============================================================= */
	/**
	 * Set Api Customer ID from Log Table
	 * @return bool
	 */
	private function setChargeAcustidFromLogTable() : bool
	{
		$charge = $this->charge;
		// Validate Customer Exists
		$CUSTOMERS	= ApiCustomersTable::instance();
		$loggedCust = $CUSTOMERS->findOneByCustid($charge->custid);
		
		if (empty($loggedCust)) {
			return false;
		}
		$charge->acustid = $loggedCust->id;
		$charge->card->acustid = $loggedCust->id;
		return true;
	}

	/**
	 * Insert Customer Log Database Record
	 * @param  CustomerDTO $customer
	 * @return bool
	 */
	private function insertCustomerLogDb(CustomerDTO $customer) : bool
	{
		$TABLE	= ApiCustomersTable::instance();
		$r = $TABLE->newRecord();
		$r->custid = $customer->custid;
		$r->id     = $customer->aid;
		return $TABLE->insert($r);
	}

/* =============================================================
	Dplus Database
============================================================= */
	/**
	 * Generate CustomerDTO from Database
	 * @param  string $custid
	 * @return CustomerDTO|false
	 */
	private function generateCustomerDTOFromDplusDb(string $custid) : CustomerDTO|false {
		$TABLE = DplusCustomersTable::instance();
		/** @var DplusCustomer */
		$dpCust = $TABLE->findOne($custid);

		if (empty($dpCust)) {
			return false;
		}
		$customer = new CustomerDTO();
		$customer->custid = $dpCust->custid;
		$customer->custname = $dpCust->name;
		$customer->billtoaddress1 = $dpCust->address1;
		$customer->billtoaddress2 = $dpCust->address2;
		$customer->billtocity	  = $dpCust->city;
		$customer->billtostate	  = $dpCust->state;
		$customer->billtozipcode  = $dpCust->zip;
		$customer->billtocountry  = $dpCust->country;

		if (empty($customer->billtocountry)) {
			$customer->billtocountry = "USA";
		}
		return $customer;
	}

/* =============================================================
	Responses
============================================================= */
	/**
	 * Return that Customer was not found
	 * @param  Payment $rqst
	 * @return Response
	 */
	private function respondCustomerNotFound(Payment $rqst) : Response
	{
		$response = new Response();
		$response->ordn = $rqst->getOrdernbr();
		$response->setApproved(false);
		$response->errorMsg = "Can't find Customer ". $rqst->getCustid() ." in database";
		return $response;
	}

	/**
	 * Return that API call is not set up
	 * @param  Payment $rqst
	 * @return Response
	 */
	private function respondCustomerCreateFailed(Payment $rqst) : Response
	{
		$response = new Response();
		$response->ordn = $rqst->getOrdernbr();
		$response->setApproved(false);
		$response->errorMsg = "Can't create Stripe Customer for " . $rqst->getCustid();
		if ($this->errorMsg) {
			$response->errorMsg = $this->errorMsg;
		}
		return $response;
	}

}
