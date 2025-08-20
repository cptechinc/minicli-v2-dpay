<?php namespace Dpay\Stripe\Api\Services\Charges;
// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
// Lib
use Dpay\Abstracts\Api\Services\Charges\CapturePreAuthChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\Stripe\Api\Endpoints;

/**
 * CapturePreAuthCharge
 * Service to Capture Pre-authorized CreditCard Charge using Stripe API
 * NOTE: uses PaymentIntents API
 * 
 * @property string          $id          Generated Credit Charge ID
 * @property DpayCharge      $dpayCharge  Charge Data
 * @property StripeCharge    $sCharge     Stripe API Charge
 */
class CapturePreAuthCharge extends AbstractCrudCharge implements CapturePreAuthChargeInterface {
	const ACTION_DESCRIPTION = 'capture pre-authorized';
	const ACTIONABLE_STATUSES = [
		'requires_confirmation' => 'requires_confirmation',
		'requires_capture'      => 'requires_capture',
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
			$this->errorMsg = "Cannot capture charge with status: {$this->sCharge->status}";
			return false;
		}
		if ($this->sCharge->status == 'requires_confirmation') {
			return $this->preProcessRequireConfirmation();
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
		$data->action = 'capture';
		return $data;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Capture Pre-Authorized, Confirmed Charge
	 * @param  StripeCharge $data
	 * @return StripeCharge|false
	 */
	protected function processCharge(StripeCharge $data) : StripeCharge|false
	{
		$stripeCharge = Endpoints\Charges::capturepreauth($data);

		if (empty($stripeCharge->id) === false) {
			return $stripeCharge;
		}
		$this->errorMsg = Endpoints\Charges::$errorMsg;
		return false;
	}

	/**
	 * Confirm Charge
	 * NOTE: use before capturing
	 * @return bool
	 */
	private function preProcessRequireConfirmation() : bool
	{
		$service = new ConfirmCharge();
		$service->setId($this->id);
		$service->setDpayCharge($this->dpayCharge);

		if ($service->process() === false) {
			$this->errorMsg = $service->errorMsg;
			return false;
		}
		if ($service->sCharge->status != 'requires_capture') {
			$this->errorMsg = "Cannot capture charge with status: {$this->sCharge->status}";
			return false;
		}
		$this->sCharge = $service->sCharge;
		return true;
	}
}