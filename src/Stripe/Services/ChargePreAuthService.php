<?php namespace Dpay\Stripe\Services;
// Stripe
use Stripe\PaymentIntent as StripeCharge;
// Dplus Payments Model
use Payment;
// DTOs
use Dpay\Data\Charge as ChargeDTO;
use Dpay\Data\PaymentResponse as Response;
// Stripe Services
use Dpay\Stripe\Api\Services\Charges\PreAuthCharge;

/**
 * ChargePreAuthService
 * 
 * Handles Creating Pre-Auth transaction
 * 
 * @property string    $errorMsg
 * @property Response  $lastResponse
 * @property ChargeDTO $charge
 * @property Payment   $rqst
 * @property StripeCharge $sCharge
 */
class ChargePreAuthService extends AbstractChargeService {
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
		if ($this->createTransaction() === false) {
			$this->lastResponse = $this->responseFailedTransaction();
			return false;
		}

		$this->insertChargeLogDb($this->charge);
		$this->insertChargeStatusLogDb($this->charge);
		return true;
	}

/* =============================================================
	API Service Calls
============================================================= */
	/**
	 * Create Transaction
	 * @return bool
	 */
	private function createTransaction() : bool
	{
		$charge = $this->charge;
		$SERVICE = new PreAuthCharge();
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
		$response->errorMsg = $this->errorMsg ? $this->errorMsg : "Unable to generate Pre-Auth Transaction";
		return $response;
	}
}
