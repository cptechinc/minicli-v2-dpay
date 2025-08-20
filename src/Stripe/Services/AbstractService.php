<?php namespace Dpay\Stripe\Services;
// Dplus Payments Model
use Payment;
// Dpay
use Dpay\Data\Charge as ChargeDTO;
use Dpay\Data\PaymentResponse as Response;

/**
 * AbstractService
 * Template for Services
 * 
 * @property string    $errorMsg
 * @property Response  $lastResponse
 * @property ChargeDTO $charge
 * @property Payment   $rqst
 */
abstract class AbstractService {
	public string $errorMsg;
	public Response $lastResponse;
	protected ChargeDTO $charge;
	protected Payment $rqst;

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
	Responses
============================================================= */
	/**
	 * Return that transaction failed
	 * @return Response
	 */
	protected function responseFailed() : Response
	{
		$response = new Response();
		$response->type = $this->rqst->type;
		$response->ordn = $this->charge->ordernbr;
		$response->setApproved(false);
		return $response;
	}
}
