<?php namespace Dpay\Stripe\Api\Services\Charges;
// Stripe API Library
use Dpay\Data\Charge;
use Stripe\PaymentIntent as StripeCharge;
// Lib
use Dpay\Abstracts\Api\Services\Charges\CaptureChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\Stripe\Api\Endpoints;

/**
 * Capture
 * Service to capture new CreditCard Charge using Stripe API
 * NOTE: uses PaymentIntents API
 * 
 * @property string          $id          Generated Credit Charge ID
 * @property DpayCharge      $dpayCharge  Charge Data
 * @property StripeCharge    $sCharge     Stripe API Charge
 */
class CaptureCharge extends AbstractCrudCharge implements CaptureChargeInterface {
	const ACTION_DESCRIPTION = 'capture';
	public StripeCharge $sCharge;
	protected DpayCharge $dpayCharge;

/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Return Response data as Dpay Credit Charge
	 * @return DpayCharge
	 */
	public function getDpayChargeResponseData() : Charge
	{
		$data = parent::getDpayChargeResponseData();
		$data->action = 'capture';
		return $data;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Generate Credit Charge Data
	 * @param  DpayCharge $charge
	 * @return StripeCharge
	 */
	protected function generateChargeRequest(DpayCharge $charge) : StripeCharge
	{
		$data = parent::generateChargeRequest($charge);
		$data->capture_method = 'automatic';
		$data->automatic_payment_methods = [
			'enabled' => true,
			'allow_redirects' => 'never'
		];
		$data->confirm = true;
		return $data;
	}

	/**
	 * Create Stripe Customer
	 * @param  StripeCharge $data
	 * @return StripeCharge|false
	 */
	protected function processCharge(StripeCharge $data) : StripeCharge|false
	{
		$stripeCharge = Endpoints\Charges::create($data);

		if (empty($stripeCharge->id) === false) {
			return $stripeCharge;
		}
		$this->errorMsg = Endpoints\Charges::$errorMsg;
		return false;
	}
}