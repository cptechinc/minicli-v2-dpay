<?php namespace Dpay\Stripe\Services;
// Stripe
use Stripe\PaymentIntent as StripeCharge;
// Dplus Payments Model
use Payment;
// DTOs
use Dpay\Data\Charge as ChargeDTO;
use Dpay\Data\PaymentResponse as Response;
// Stripe Services
use Dpay\Stripe\Api\Services\Charges\VoidCharge;

/**
 * ChargeVoidService
 * 
 * Handles Voiding Charge
 * 
 * @property string    $errorMsg
 * @property Response  $lastResponse
 * @property ChargeDTO $charge
 * @property Payment   $rqst
 * @property StripeCharge $sCharge
 */
class ChargeVoidService extends AbstractChargeService {
	public string $errorMsg;
	public Response $lastResponse;
	protected ChargeDTO $charge;
	protected Payment $rqst;
	protected StripeCharge $sCharge;

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
		if ($this->validateStripeCharge() === false) {
			return false;
		}
		if ($this->void() === false) {
			return false;
		}
		$this->insertChargeLogDb($this->charge);
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
			$this->lastResponse = $this->responseFailedTransaction();
			return false;
		}

		$sCharge = $this->fetchStripeCharge($this->charge->transactionid);

		if (empty($sCharge->id)) {
			$this->errorMsg = "Transaction not found";
			$this->lastResponse = $this->responseFailedTransaction();
			return false;
		}
		$this->sCharge = $sCharge;
		return true;
	}

	/**
	 * Validate if Stripe Charge can be voided
	 * @return bool
	 */
	private function validateStripeCharge() : bool
	{
		$sCharge = $this->sCharge;
		
		if (array_key_exists($sCharge->status, VoidCharge::ACTIONABLE_STATUSES) === false) {
			$this->errorMsg = "Unable to void status $sCharge->status";
			$this->lastResponse = $this->responseFailedCaptureByStatus();
			return false;
		}
		return true;
	}

/* =============================================================
	API Service Calls
============================================================= */
	/**
	 * Void Trnsaction
	 * @return bool
	 */
	private function void() : bool
	{
		$charge = $this->charge;
		$SERVICE = new VoidCharge();
		$SERVICE->setId($charge->transactionid);
		$SERVICE->setDpayCharge($charge);

		if ($SERVICE->process() === false) {
			$this->errorMsg = $SERVICE->errorMsg;
			return false;
		}

		/** @var StripeCharge */
		$sCharge = $SERVICE->sCharge;

		if (empty($sCharge->id)) {
			$this->errorMsg = $SERVICE->errorMsg;
			return false;
		}
		$charge->transactionid = $sCharge->id;
		$charge->status = $sCharge->status;
		$this->sCharge  = $sCharge;
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
		$response->errorMsg = $this->errorMsg ? $this->errorMsg : "Unable to void Transaction";
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
		$response->errorMsg = $this->errorMsg ? $this->errorMsg : "Unable to void transaction with status";
		return $response;
	}
}
