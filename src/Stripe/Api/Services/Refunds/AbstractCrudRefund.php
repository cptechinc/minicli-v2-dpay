<?php namespace Dpay\Stripe\Api\Services\Refunds;
// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
use Stripe\Refund as StripeRefund;
// Lib
use Dpay\Stripe\Api\AbstractService;
use Dpay\Stripe\Api\Endpoints;
use Dpay\Data\Refund as DpayRefund;
use Dpay\Stripe\Api\Services\Charges\Util\ChargeStatus;

/**
 * AbstractCrudRefund
 * Service to Create Refund using Stripe API
 * 
 * @property string 	     $id		   API Refund ID
 * @property DpayRefund		 $dpayRefund   Dpay Refund (Request)
 * @property StripeRefund 	 $sRefund 	   Stripe API Refund
 * @property StripeCharge 	 $sCharge 	   Stripe API Charge
 */
abstract class AbstractCrudRefund extends AbstractService {
	const ACTION_DESCRIPTION = 'update';
	protected string $id;
	public StripeRefund $sRefund;
	protected DpayRefund $dpayRefund;
	protected StripeCharge $sCharge;
	
/* =============================================================
	Inits
============================================================= */
	/**
	 * Init Dpay Refund
	 * @return bool
	 */
	protected function initDpayRefund() : bool
	{
		if (empty($this->dpayRefund)) {
			$this->errorMsg = 'Refund Data not set';
			return false;
		}
		return true;
	}

	/**
	 * Fetch Stripe Charge, verify if status can be acted on
	 * @return bool
	 */
	protected function initStripeCharge() : bool
	{
		$this->sCharge = Endpoints\Charges::fetchById($this->dpayRefund->transactionid);

		if (empty($this->sCharge) || empty($this->sCharge->id)) {
			$this->errorMsg = 'Charge not found';
			return false;
		}
		return true;
	}
	
/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Set Dpay Credit Refund
	 * @param  DpayRefund $refund
	 * @return void
	 */
	public function setDpayRefund(DpayRefund $dpayRefund) : void
	{
		$this->dpayRefund = $dpayRefund;
	}

	/**
	 * Return Dpay Credit Refund
	 * @return DpayRefund
	 */
	public function getDpayRefund() : DpayRefund
	{
		return $this->dpayRefund;
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
	 * Return API Credit Refund ID
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
		if ($this->initDpayRefund() === false) {
			return false;
		}
		if ($this->initStripeCharge() === false) {
			return false;
		}
		$rqst         = $this->generateRefundRequest($this->dpayRefund);
		$stripeRefund = $this->processRefund($rqst);

		if (empty($stripeRefund)) {
			if ($this->errorMsg) {
				return false;
			}
			$this->errorMsg = "Unable to " . static::ACTION_DESCRIPTION . " charge {$this->dpayRefund->charge->custid}";
			return false;
		}
		$this->sRefund = $stripeRefund;
		$this->id	   = $stripeRefund->id;
		return true;
	}

	/**
	 * Return Response data as Dpay Refund
	 * @return DpayRefund
	 */
	public function getDpayRefundResponseData() : DpayRefund
	{
		$refund = $this->sRefund;
		$data   = $refund->toArray();

		$data = new DpayRefund();
		$data->refundid = $refund->id;
		$data->transactionid = $refund->payment_intent;
		$data->charge->transactionid = $refund->payment_intent;
		$data->amount   = $refund->amount / 100;
		$data->status   = ChargeStatus::find($refund->status)->value;
		return $data;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Generate Credit Refund Request Data
	 * @param  DpayRefund $refund
	 * @return StripeRefund
	 */
	protected function generateRefundRequest(DpayRefund $refund) : StripeRefund
	{
		$data = new StripeRefund();
		if ($refund->refundid) {
			$data = new StripeRefund($refund->refundid);
		}
		$data->amount = $refund->amount * 100;
		$data->metadata = ['custid' => $refund->charge->custid, 'ordernbr' => $refund->charge->ordernbr];
		$data->payment_intent = $refund->transactionid;
		$data->reason = 'requested_by_customer';
		return $data;
	}

    /**
     * Creates Stripe Credit Refund
     * @param  StripeRefund $data
     * @return StripeRefund|false
     */
	abstract protected function processRefund(StripeRefund $data) : StripeRefund|false;
}