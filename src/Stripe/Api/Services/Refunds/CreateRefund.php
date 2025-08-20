<?php namespace Dpay\Stripe\Api\Services\Refunds;
// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
use Stripe\Refund as StripeRefund;
// Lib
use Dpay\Abstracts\Api\Services\Refunds\CreateRefundInterface;
use Dpay\Data\Refund as DpayRefund;
use Dpay\Stripe\Api\Endpoints;

/**
 * CreateRfund
 * Service to Refund using Stripe API
 * 
 * @property string          $id          Generated Credit Refund ID
 * @property DpayRefund      $dpayRefund  Refund Data
 * @property StripeRefund    $sRefund     Stripe API Refund
 * @property StripeCharge 	 $sCharge 	   Stripe API Charge
 */
class CreateRefund extends AbstractCrudRefund implements CreateRefundInterface {
	const ACTION_DESCRIPTION = 'create';
	public StripeRefund $sRefund;
	protected DpayRefund $dpayRefund;

/* =============================================================
	Interface Contracts
============================================================= */

/* =============================================================
	Internal Processing
============================================================= */

	/**
	 * Create Stripe Customer
	 * @param  StripeRefund $data
	 * @return StripeRefund|false
	 */
	protected function processRefund(StripeRefund $data) : StripeRefund|false
	{
		$stripeRefund = Endpoints\Refunds::create($data);

		if (empty($stripeRefund->id) === false) {
			return $stripeRefund;
		}
		$this->errorMsg = Endpoints\Refunds::$errorMsg;
		return false;
	}
}