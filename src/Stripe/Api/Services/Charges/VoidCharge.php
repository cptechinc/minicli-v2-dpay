<?php namespace Dpay\Stripe\Api\Services\Charges;
// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
// Lib
use Dpay\Abstracts\Api\Services\Charges\VoidChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\Stripe\Api\Endpoints;

/**
 * VoidCharge
 * Service to Void / Cancel CreditCard Charge using Stripe API
 * NOTE: uses PaymentIntents API
 * 
 * @property string          $id          Generated Credit Charge ID
 * @property DpayCharge      $dpayCharge  Charge Data
 * @property StripeCharge    $sCharge     Stripe API Charge
 */
class VoidCharge extends AbstractCrudCharge implements VoidChargeInterface {
	const ACTION_DESCRIPTION = 'void';
	const ACTIONABLE_STATUSES = [
		'requires_action'         => 'requires_action',
		'requires_payment_method' => 'requires_payment_method',
		'requires_confirmation'   => 'requires_confirmation',
		'requires_capture'        => 'requires_capture',
	];
	const REASONS = [
		'duplicate'             => 'duplicate',
		'fraudulent'            => 'fraudulent',
		'requested_by_customer' => 'requested_by_customer',
		'abandoned'             => 'abandoned',
	];
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
		if (array_key_exists($this->sCharge->status, self::ACTIONABLE_STATUSES) === false) {
			$this->errorMsg = "Cannot cancel charge with status: {$this->sCharge->status}";
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
		$data->action = 'void';
		$data->transactiontype = 'void';
		return $data;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Cancel Charge
	 * @param  StripeCharge $data
	 * @return StripeCharge|false
	 */
	protected function processCharge(StripeCharge $data) : StripeCharge|false
	{
		$data->cancellation_reason = 'abandoned';
		$stripeCharge = Endpoints\Charges::cancel($data);

		if (empty($stripeCharge->id) === false) {
			return $stripeCharge;
		}
		$this->errorMsg = Endpoints\Charges::$errorMsg;
		return false;
	}
}