<?php namespace Dpay\Stripe\Services;
// Stripe
use Stripe\PaymentIntent as StripeCharge;
// Dplus Payments Model
use Payment;
// DTOs
use Dpay\Data\Charge as ChargeDTO;
use Dpay\Data\PaymentResponse as Response;

/**
 * ChargeCaptureService
 * 
 * Handles Creating and Capturing Charge
 * 
 * @property string    $errorMsg
 * @property Response  $lastResponse
 * @property ChargeDTO $charge
 * @property Payment   $rqst
 * @property StripeCharge $sCharge
 */
class ChargeCaptureService extends ChargeCapturePreAuthService {
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
		$SERVICE = new ChargePreAuthService();
		$SERVICE->setCharge($this->charge);

		if ($SERVICE->process() === false) {
			$this->errorMsg     = $SERVICE->errorMsg;
			$this->lastResponse = $this->responseFailedTransaction();
			return false;
		}
		$this->sCharge = $SERVICE->getStripeCharge();
		return true;
	}

/* =============================================================
	API Service Calls
============================================================= */
	
/* =============================================================
	Responses
============================================================= */

}
