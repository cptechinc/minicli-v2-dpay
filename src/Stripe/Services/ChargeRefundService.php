<?php namespace Dpay\Stripe\Services;
// Stripe
use Stripe\Refund as StripeRefund;
// Dplus Payments Model
use Payment;
// DTOs
use Dpay\Data\Charge as ChargeDTO;
use Dpay\Data\Refund as RefundDTO;
use Dpay\Data\PaymentResponse as Response;
// App Database
use Lib\Logs\Database\Refunds as RefundsTable;
// Stripe Services
use Dpay\Stripe\Api\Services\Refunds\CreateRefund;

/**
 * RefundService
 * 
 * Handles Refunding Charge
 * 
 * @property string    $errorMsg
 * @property Response  $lastResponse
 * @property ChargeDTO $charge
 * @property RefundDTO $refund
 * @property Payment   $rqst
 */
class ChargeRefundService extends AbstractChargeService {
	public string $errorMsg;
	public Response $lastResponse;
	protected ChargeDTO $charge;
	protected Payment $rqst;
	public RefundDTO $refund;

	public function __construct() {
		parent::__construct();
		$this->refund = new RefundDTO();
	}

/* =============================================================
	Public
============================================================= */
	/**
	 * Process Request
	 * @return bool
	 */
	public function process() : bool
	{
		if ($this->initStripeCharge() === false) {
			return false;
		}
		if ($this->initDpayRefund() === false) {
			return false;
		}
		if ($this->refund() === false) {
			$this->lastResponse = $this->responseFailedTransaction();
			return false;
		}
		$this->charge->status = 'refunded';
		$this->insertRefundLogDb($this->refund);
		$this->insertChargeStatusLogDb($this->charge);
		return true;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Initialize Stripe Charge
	 * @return bool
	 */
	private function initStripeCharge() : bool
	{
		if (empty($this->charge->transactionid)) {
			$this->errorMsg = "Transaction not set";
			return false;
		}

		$sCharge = $this->fetchStripeCharge($this->charge->transactionid);

		if (empty($sCharge->id)) {
			$this->errorMsg = "Transaction not found";
			return false;
		}
		return true;
	}

	/**
	 * Initialize Refund Data
	 * @return bool
	 */
	private function initDpayRefund() : bool
	{
		$refund = $this->refund;
		$refund->charge = $this->charge;
		$refund->amount = $this->charge->amount;
		$refund->transactionid = $this->charge->transactionid;
		return true;
	}

/* =============================================================
	API Service Calls
============================================================= */
	/**
	 * Send Refund request
	 * @return bool
	 */
	private function refund() : bool
	{
		$refund = $this->refund;
		$SERVICE = new CreateRefund();
		$SERVICE->setDpayRefund($refund);

		if ($SERVICE->process() === false) {
			$this->errorMsg = $SERVICE->errorMsg;
			$this->lastResponse = $this->responseFailedTransaction();
			return false;
		}

		/** @var StripeRefund */
		$sRefund = $SERVICE->sRefund;

		if (empty($sRefund->id)) {
			$this->errorMsg = $SERVICE->errorMsg;
			$this->lastResponse = $this->responseFailedTransaction();
			return false;
		}
		$refund->refundid = $sRefund->id;
		$refund->status   = $sRefund->status;
		return true;
	}
	
/* =============================================================
	Responses
============================================================= */
	/**
	 * Return that transaction failed
	 * @return Response
	 */
	private function responseFailedTransaction() : Response
	{
		$charge = $this->charge;
		$response = new Response();
		$response->ordn = $charge->ordernbr;
		$response->setApproved(false);
		$response->errorMsg = $this->errorMsg ? $this->errorMsg : "Unable to refund Transaction";
		return $response;
	}

	/**
	 * Return that transaction failed because of status
	 * @return Response
	 */
	private function responseFailedCaptureByStatus() : Response
	{
		$charge = $this->charge;
		$response = new Response();
		$response->ordn = $charge->ordernbr;
		$response->setApproved(false);
		$response->errorMsg = $this->errorMsg ? $this->errorMsg : "Unable to refund transaction with status";
		return $response;
	}

/* =============================================================
	App Database
============================================================= */
	/**
	 * Insert Refund Log Database Record
	 * @param  RefundDTO $charge
	 * @return bool
	 */
	protected function insertRefundLogDb(RefundDTO $refund) : bool
	{
		$TABLE = RefundsTable::instance();

		$charge = $this->refund->charge;

		$r = $TABLE->newRecord();
		$r->custid  = $charge->custid;
		$r->acustid = $charge->acustid;
		$r->amount  = $this->refund->amount;
		$r->refundid        = $this->refund->refundid;
		$r->transactionid   = $this->refund->transactionid;
		$r->status = $this->refund->status;
		return $TABLE->insert($r);
	}
}
