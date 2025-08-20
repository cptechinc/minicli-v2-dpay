<?php namespace Dpay\Stripe\Services;
// Stripe
use Stripe\PaymentIntent as StripeCharge;
// Dplus Payments Model
use Payment;
// DTOs
use Dpay\Data\Charge as ChargeDTO;
use Dpay\Data\PaymentResponse as Response;
// App Database
use Lib\Logs\Database\Charges as ApiChargesTable;
use Lib\Logs\Database\ChargeStatuses as ApiChargeStatusesTable;
// Stripe Services
use Dpay\Stripe\Api\Services\Charges\FetchCharge;

/**
 * AbstractService
 * Template for Services that deal with charges
 * 
 * @property string    $errorMsg
 * @property Response  $lastResponse
 * @property ChargeDTO $charge
 * @property Payment   $rqst
 * @property StripeCharge $sCharge
 */
abstract class AbstractChargeService {
	public string $errorMsg;
	public Response $lastResponse;
	protected ChargeDTO $charge;
	protected Payment $rqst;
	protected StripeCharge $sCharge;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		$this->errorMsg = '';
		$this->lastResponse = new Response();
		$this->charge  = new ChargeDTO();
		$this->rqst    = new Payment();
		$this->sCharge = new StripeCharge();
	}

/* =============================================================
	Public
============================================================= */
	/**
	 * Process Request
	 * @return bool
	 */
	abstract public function process() : bool;

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set Charge
	 * @param  ChargeDTO $charge
	 * @return void
	 */
	public function setCharge(ChargeDTO $charge) : void
	{
		$this->charge = $charge;
	}

	/**
	 * Set Payment Request
	 * @param  Payment $rqst
	 * @return void
	 */
	public function setPayment(Payment $rqst) : void
	{
		$this->rqst = $rqst;
	}

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return Charge
	 * @return ChargeDTO
	 */
	public function getCharge() : ChargeDTO 
	{
		return $this->charge;
	}

	/**
	 * Return Stripe Charge
	 * @return StripeCharge
	 */
	public function getStripeCharge() : StripeCharge
	{
		return $this->sCharge;
	}

/* =============================================================
	App Database
============================================================= */
	/**
	 * Insert Charge Log Database Record
	 * @param  ChargeDTO $charge
	 * @return bool
	 */
	protected function insertChargeLogDb(ChargeDTO $charge) : bool
	{
		$TABLE	= ApiChargesTable::instance();
		$r = $TABLE->newRecord();
		$r->custid          = $charge->custid;
		$r->acustid         = $charge->acustid;
		$r->ordernbr        = $charge->ordernbr;
		$r->transactiontype = $charge->transactiontype;
		$r->amount          = $charge->amount;
		$r->transactionid   = $charge->transactionid;
		$r->status          = $charge->status;
		$r->action          = $charge->action;
		return $TABLE->insert($r);
	}

	/**
	 * Insert Charge Status Log Database Record
	 * @param  ChargeDTO $charge
	 * @return bool
	 */
	protected function insertChargeStatusLogDb(ChargeDTO $charge) : bool
	{
		$TABLE	= ApiChargeStatusesTable::instance();
		$r = $TABLE->findOneByTransactionid($charge->transactionid);

		if (empty($r)) {
			$r = $TABLE->newRecord();
			$r->transactionid = $charge->transactionid;
			$r->ordernbr      = $charge->ordernbr;
			$r->custid        = $charge->custid;
			$r->acustid       = $charge->acustid;
		}
		$r->status = $charge->status;
		$r->amount = $charge->amount;
		return $TABLE->insertOrUpdate($r);
	}

/* =============================================================
	API Service Calls
============================================================= */
	/**
	 * Return StripeCharge
	 * @param  string $id
	 * @return StripeCharge
	 */
	protected function fetchStripeCharge(string $id) : StripeCharge
	{
		$SERVICE = new FetchCharge();
		$SERVICE->setId($id);

		if ($SERVICE->process() === false) {
			$this->errorMsg = $SERVICE->errorMsg;
			return new StripeCharge();
		}
		return $SERVICE->sCharge;
	}
}
