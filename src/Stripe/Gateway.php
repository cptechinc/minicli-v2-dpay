<?php namespace Dpay\Stripe;
// Dplus Payments Model
use Payment;
// Dpay
use Dpay\Data\Charge as ChargeDTO;
use Dpay\Data\PaymentResponse as ResponseData;
use Dpay\Abstracts\AbstractGateway;
// Sub Services
use Dpay\Stripe\Services\CreditCardsService;
use Dpay\Stripe\Services\CustomersService;
use Dpay\Stripe\Services\AbstractChargeService;
use Dpay\Stripe\Services\ChargeCapturePreAuthService;
use Dpay\Stripe\Services\ChargeCaptureService;
use Dpay\Stripe\Services\ChargePreAuthService;
use Dpay\Stripe\Services\ChargeRefundService;
use Dpay\Stripe\Services\ChargeVoidService;

/**
 * Gateway
 * Wrapper For making API calls to Stripe
 * 
 * @property ApiClient $stripe
 */
class Gateway extends AbstractGateway {
	const ENV_REQUIRED = [
		'STRIPE.SECRET.KEY',
	];

	public function init() : void
	{
		$this->stripe = ApiClient::instance();
	}

/* =============================================================
	1. Contract Functions
============================================================= */
	/**
	 * Processes Request, sends Transaction Request to API
	 * @param  Payment $request
	 * @return ResponseData
	 */
	public function process(Payment $request) : ResponseData
	{
		$this->lastResponse = new ResponseData();
		$this->lastResponse->type = $request->type;

		if ($this->stripe->connect() === false) {
			return $this->respondApiNotConnected($request);
		}
		return parent::process($request);
	}
	
	/**
	 * Capture new charge
	 * @param  Payment  $rqst
	 * @return ResponseData
	 */
	protected function capture(Payment $rqst) : ResponseData
	{
		$charge = new ChargeDTO();
		$charge->action = 'capture';
		if ($this->setupCharge($charge,$rqst) === false) {
			return $this->lastResponse;
		}
		$SERVICE = new ChargeCaptureService();
		$this->processServiceTransaction($SERVICE, $charge, $rqst);
		return $this->lastResponse;
	}
	
	/**
	 * Capture pre-authorized charge
	 * @param  Payment $rqst
	 * @return ResponseData
	 */
	protected function capturePreauthorized(Payment $rqst) : ResponseData
	{
		$charge = new ChargeDTO();
		$charge->action = 'capturepreauth';
		if ($this->setupCharge($charge,$rqst) === false) {
			return $this->lastResponse;
		}
		$SERVICE = new ChargeCapturePreAuthService();
		$this->processServiceTransaction($SERVICE, $charge, $rqst);
		return $this->lastResponse;
	}

	/**
	 * Pre-authorize charge
	 * @param  Payment $rqst
	 * @return ResponseData
	 */
	protected function preauthorize(Payment $rqst) : ResponseData 
	{
		$charge = new ChargeDTO();
		$charge->action = 'preauth';
		if ($this->setupCharge($charge,$rqst) === false) {
			return $this->lastResponse;
		}
		$SERVICE = new ChargePreAuthService();
		$this->processServiceTransaction($SERVICE, $charge, $rqst);
		return $this->lastResponse;
	}

	/**
	 * Refund Charge
	 * @param  Payment $rqst
	 * @return ResponseData
	 */
	protected function refund(Payment $rqst) : ResponseData
	{
		$charge = new ChargeDTO();
		$charge->action = 'refund';
		if ($this->setupCharge($charge,$rqst) === false) {
			return $this->lastResponse;
		}
		$SERVICE = new ChargeRefundService();
		$this->processServiceTransaction($SERVICE, $charge, $rqst);
		return $this->lastResponse;
	}

	/**
	 * Void Charge
	 * @param  Payment $rqst
	 * @return ResponseData
	 */
	protected function void(Payment $rqst) : ResponseData
	{
		$charge = new ChargeDTO();
		$charge->action = 'void';
		if ($this->setupCharge($charge,$rqst) === false) {
			return $this->lastResponse;
		}
		$SERVICE = new ChargeVoidService();
		$this->processServiceTransaction($SERVICE, $charge, $rqst);
		return $this->lastResponse;
	}

/* =============================================================
	2. API Requests
============================================================= */
	/**
	 * Process Charge through Service
	 * @param  AbstractChargeService $SERVICE
	 * @param  ChargeDTO             $charge
	 * @return bool
	 */
	private function processServiceTransaction(AbstractChargeService $SERVICE, ChargeDTO $charge, Payment $rqst) : bool
	{
		$SERVICE->setCharge($charge);
		
		if ($SERVICE->process() === false) {
			$this->lastResponse = $SERVICE->lastResponse;
			return false;
		}
		$response = new ResponseData();
		$response->ordn = $charge->ordernbr;
		$response->setApproved(true);
		$response->transactionid = $charge->transactionid;
		$response->type = $rqst->type;
		$this->lastResponse      = $response;
		return true;
	}

/* =============================================================
	3. Responses
============================================================= */

/* =============================================================
	4. Internal
============================================================= */
	/**
	 * Create Customer
	 * @param  Payment    $rqst
	 * @param  ChargeDTO  $charge
	 * @return bool
	 */
	private function setupCustomer(Payment $rqst, ChargeDTO $charge) : bool
	{
		$SERVICE = new CustomersService();
		$SERVICE->setPayment($rqst);
		$SERVICE->setCharge($charge);

		if ($SERVICE->process() === false) {
			$this->lastResponse = $SERVICE->lastResponse;
			return false;
		}
		return true;
	}
	
	/**
	 * Create Customer CreditCard
	 * @param  Payment    $rqst
	 * @param  ChargeDTO  $charge
	 * @return bool
	 */
	private function setupCreditCard(Payment $rqst, ChargeDTO $charge) : bool
	{
		$SERVICE = new CreditCardsService();
		$SERVICE->setPayment($rqst);
		$SERVICE->setCharge($charge);

		if ($SERVICE->process() === false) {
			$this->lastResponse = $SERVICE->lastResponse;
			return false;
		}
		return true;
	}

	/**
	 * Create Customer and CreditCard
	 * @param  Payment    $rqst
	 * @param  ChargeDTO  $charge
	 * @return bool
	 */
	private function setupCustomerAndCreditCard(Payment $rqst, ChargeDTO $charge) : bool
	{
		if ($this->setupCustomer($rqst, $charge) === false) {
			return false;
		}
		if ($this->setupCreditCard($rqst, $charge) === false) {
			return false;
		}
		return true;
	}

	/**
	 * Set Charge Fields from Payment Request
	 * @param  ChargeDTO $charge
	 * @param  Payment   $rqst
	 * @return bool
	 */
	private function setChargeFieldsFromPayment(ChargeDTO $charge, Payment $rqst) {
		$charge->ordernbr         = $rqst->getOrdernbr();
		$charge->custid           = $rqst->getCustid();
		$charge->amount           = $rqst->getAmount();
		$charge->card->custid     = $rqst->getCustid();
		$charge->card->name       = $rqst->getCardName();
		$charge->card->cardnbr    = $rqst->cardnumber();
		$charge->card->cvc        = $rqst->cvv();
		$charge->card->expiredate = $rqst->expiredate();
		$charge->card->address1   = $rqst->getStreet();
		$charge->card->zipcode    = $rqst->getZipcode();
		$charge->transactiontype  = $rqst->type;
		$charge->transactionid    = $rqst->getTransId();
		return true;
	}

	/**
	 * Setup Charge, CreditCard, Customer
	 * @param  ChargeDTO $charge
	 * @param  Payment   $rqst
	 * @return bool
	 */
	private function setupCharge(ChargeDTO $charge, Payment $rqst) : bool
	{
		$this->setChargeFieldsFromPayment($charge, $rqst);

		if ($this->setupCustomerAndCreditCard($rqst,  $charge) === false) {
			return false;
		}
		return true;
	}
}