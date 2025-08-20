<?php namespace Dpay\Stripe\Api\Services\Charges;
// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
// Lib
use Dpay\Abstracts\Api\Services\Charges\UpdateChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\Stripe\Api\Endpoints;

/**
 * Update
 * Service to update CreditCard Charge using Stripe API
 * NOTE: uses PaymentIntents API
 * 
 * @property string          $id          Generated Credit Charge ID
 * @property DpayCharge      $dpayCharge  Charge Data
 * @property StripeCharge    $sCharge     Stripe API Charge
 */
class UpdateCharge extends AbstractCrudCharge implements UpdateChargeInterface {
	const ACTION_DESCRIPTION = 'update';
	public StripeCharge $sCharge;
	protected DpayCharge $dpayCharge;

/* =============================================================
	Inits
============================================================= */
	/**
	 * Fetch Stripe Charge, verify if status can be acted on
	 * @return bool
	 */
	protected function initStripeCharge() : bool
	{
		$this->sCharge = Endpoints\Charges::fetchById($this->id);

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
	 * Process Request
	 * @return bool
	 */
	public function process() : bool
	{
		if ($this->initDpayCharge() === false) {
			return false;
		}
		$this->id = $this->dpayCharge->transactionid;
		if ($this->initStripeCharge() === false) {
			return false;
		}
		return parent::process();
	}
	/**
	 * Return Response data as Dpay Credit Charge
	 * @return DpayCharge
	 */
	public function getDpayChargeResponseData() : DpayCharge
	{
		$data = parent::getDpayChargeResponseData();
		$data->action = 'update';
		return $data;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Create Stripe Customer
	 * @param  StripeCharge $data
	 * @return StripeCharge|false
	 */
	protected function processCharge(StripeCharge $data) : StripeCharge|false
	{
		$stripeCharge = Endpoints\Charges::update($data);

		if (empty($stripeCharge->id) === false) {
			return $stripeCharge;
		}
		$this->errorMsg = Endpoints\Charges::$errorMsg;
		return false;
	}
}