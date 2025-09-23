<?php namespace Dpay\Stripe\Api\Services\Charges;
// Stripe API Library
use Dpay\Data\Charge;
use Stripe\PaymentIntent as StripeCharge;
// Lib
use Dpay\Data\Charge as DpayCharge;
use Dpay\Stripe\Api\AbstractService;
use Dpay\Stripe\Api\Services\Charges\Util\ChargeStatus;

/**
 * AbstractCrudCharge
 * Service to Create Credit Charge using Stripe API
 * 
 * @property string 	     $id		   API Charge ID
 * @property DpayCharge		 $dpayCharge   Dpay Credit Charge (Request)
 * @property StripeCharge 	 $sCharge 	   Stripe API Credit Charge
 */
abstract class AbstractCrudCharge extends AbstractService {
	const ACTION = 'update';
	const ACTION_DESCRIPTION = 'update';
	public string $id = '';
	public StripeCharge $sCharge;
	protected DpayCharge $dpayCharge;
	
/* =============================================================
	Inits
============================================================= */
	/**
	 * Init Dpay Charge
	 * @return bool
	 */
	protected function initDpayCharge() : bool
	  {
		if (empty($this->dpayCharge)) {
			$this->errorMsg = 'Charge Data not set';
			return false;
		}
		return true;
	}
	
/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Set Dpay Credit Charge
	 * @param  DpayCharge $charge
	 * @return void
	 */
	public function setDpayCharge(DpayCharge $dpayCharge) : void
	{
		$this->dpayCharge = $dpayCharge;
	}

	/**
	 * Return Dpay Credit Charge
	 * @return DpayCharge
	 */
	public function getDpayCharge() : Charge
	{
		return $this->dpayCharge;
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
	 * Return API Credit Charge ID
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
		if ($this->initDpayCharge() === false) {
			return false;
		}
		$rqst         = $this->generateChargeRequest($this->dpayCharge);
		$stripeCharge = $this->processCharge($rqst);

		if (empty($stripeCharge)) {
			if ($this->errorMsg) {
				return false;
			}
			$this->errorMsg = "Unable to " . static::ACTION . " Credit Charge {$this->dpayCharge->custid}";
			return false;
		}
		$this->sCharge = $stripeCharge;
		$this->id	   = $stripeCharge->id;
		return true;
	}

	/**
	 * Return Response data as Dpay Charge
	 * @return DpayCharge
	 */
	public function getDpayChargeResponseData() : Charge
	{
		$charge = $this->sCharge;
		$data = $charge->toArray();
		$metadata = $charge->metadata;

		$data = new DpayCharge();
		$data->transactionid = $charge->id;
		$data->acustid = $charge->customer;
		$data->amount  = $charge->amount / 100;
		$data->transactiontype = static::ACTION;
		$data->card->aid = $charge->payment_method;
		$data->ordernbr  = $metadata->offsetExists('ordernbr') ? $metadata->ordernbr : '';
		$data->custid    = $metadata->offsetExists('custid') ? $metadata->custid : '';
		$data->status    = ChargeStatus::find($charge->status)->value;
		return $data;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Generate Credit Charge Request Data
	 * @param  DpayCharge $charge
	 * @return StripeCharge
	 */
	protected function generateChargeRequest(DpayCharge $charge) : StripeCharge
	{
		$data = new StripeCharge();
		if ($charge->transactionid) {
			$data = new StripeCharge($charge->transactionid);
		}
		$data->amount = $charge->amount * 100;
		$data->currency = 'usd';
		$data->metadata = ['custid' => $charge->custid, 'ordernbr' => $charge->ordernbr];
		$data->customer = $charge->acustid;
		$data->payment_method = $charge->card->aid;
		return $data;
	}

    /**
     * Creates Stripe Credit Charge
     * @param  StripeCharge $data
     * @return StripeCharge|false
     */
	abstract protected function processCharge(StripeCharge $data) : StripeCharge|false;
}