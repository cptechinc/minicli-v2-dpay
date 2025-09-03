<?php namespace Dpay\Stripe\Api\Services\Charges;
// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
// Lib
use Dpay\Abstracts\Api\Services\Charges\PreAuthChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\Stripe\Api\Endpoints;
use Dpay\Stripe\Config;

/**
 * PreAuth
 * Service to pre-authorize CreditCard Charge using Stripe API
 * NOTE: uses PaymentIntents API
 * 
 * @property string          $id          Generated Credit Charge ID
 * @property DpayCharge      $dpayCharge  Charge Data
 * @property StripeCharge    $sCharge     Stripe API Charge
 */
class PreAuthCharge extends AbstractCrudCharge implements PreAuthChargeInterface {
	const ACTION_DESCRIPTION = 'pre-authorize';
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
		$data->action = 'preauth';
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
		$config = Config::instance();
		$data = parent::generateChargeRequest($charge);
		$data->capture_method = 'manual';
		$data->automatic_payment_methods = [
			'enabled' => true,
			'allow_redirects' => 'never'
		];
		if ($config->autoConfirmPreauths) {
			$data->confirm = true;
		}
		return $data;
	}

	/**
	 * Create Stripe Customer
	 * @param  StripeCharge $data
	 * @return StripeCharge|false
	 */
	protected function processCharge(StripeCharge $data) : StripeCharge|false
	{
		$stripeCharge = Endpoints\Charges::preauth($data);

		if (empty($stripeCharge->id) === false) {
			return $stripeCharge;
		}
		$this->errorMsg = Endpoints\Charges::$errorMsg;
		return false;
	}
}