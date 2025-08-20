<?php namespace Dpay\Stripe\Services;
// Stripe
use Stripe\PaymentIntent as StripeCharge;
// Dplus Payments Model
use Payment;
// DTOs
use Dpay\Data\Charge as ChargeDTO;
use Dpay\Data\PaymentResponse as Response;
// Stripe Services
use Dpay\Stripe\Api\Services\Charges\CapturePreAuthCharge;

/**
 * ChargeCapturePreAuthService
 * 
 * Handles Capturing Pre-Authorized Charge
 * 
 * @property string    $errorMsg
 * @property Response  $lastResponse
 * @property ChargeDTO $charge
 * @property Payment   $rqst
 * @property StripeCharge $sCharge
 */
class ChargeCapturePreAuthService extends AbstractChargeService {
	const MSG_FAILED_TRANSACTION = 'Unable to capture Transaction';

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
		if ($this->capture() === false) {
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
	protected function initStripeCharge() : bool
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
	 * Validate if Stripe Charge can be captured
	 * @return bool
	 */
	protected function validateStripeCharge() : bool
	{
		$sCharge = $this->sCharge;
		
		if (array_key_exists($sCharge->status, CapturePreAuthCharge::ACTIONABLE_STATUSES) === false) {
			$this->errorMsg = "Unable to capture status $sCharge->status";
			$this->lastResponse = $this->responseFailedCaptureByStatus();
			return false;
		}
		return true;
	}

/* =============================================================
	API Service Calls
============================================================= */
	/**
	 * Summary of capture
	 * @return bool
	 */
	protected function capture() : bool
	{
		$charge = $this->charge;
		$SERVICE = new CapturePreAuthCharge();
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
		$this->sCharge = $sCharge;
		return true;
	}
	
/* =============================================================
	Responses
============================================================= */
	/**
	 * Return that transaction failed because of status
	 * @return Response
	 */
	protected function responseFailedCaptureByStatus() : Response
	{
		$response = $this->responseFailedTransaction();
		$response->errorMsg = $this->errorMsg ? $this->errorMsg : "Unable to capture transaction with status";
		return $response;
	}
}
