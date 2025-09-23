<?php namespace Dpay\Stripe\Api\Services\Charges;
// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
// Lib
use Dpay\Abstracts\Api\Services\Charges\ConfirmChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\Stripe\Api\Endpoints;

/**
 * ConfirmCharge
 * Service to Confirm CreditCard Charge using Stripe API
 * NOTE: uses PaymentIntents API
 * 
 * @property string          $id          Generated Credit Charge ID
 * @property DpayCharge      $dpayCharge  Charge Data
 * @property StripeCharge    $sCharge     Stripe API Charge
 */
class ConfirmCharge extends AbstractCrudCharge implements ConfirmChargeInterface {
	const ACTION = 'confirm pre-authorized';

	public StripeCharge $sCharge;
	protected DpayCharge $dpayCharge;

/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Return Response data as Dpay Credit Charge
	 * @return DpayCharge
	 */
	public function getDpayChargeResponseData() : DpayCharge
	{
		$data = parent::getDpayChargeResponseData();
		$data->action = 'confirm';
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
		$stripeCharge = Endpoints\Charges::confirm($data);

		if (empty($stripeCharge->id) === false) {
			return $stripeCharge;
		}
		$this->errorMsg = Endpoints\Charges::$errorMsg;
		return false;
	}
}