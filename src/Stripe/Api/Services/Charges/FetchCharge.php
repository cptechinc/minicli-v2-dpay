<?php namespace Dpay\Stripe\Api\Services\Charges;
// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
// Lib
use Dpay\Abstracts\Api\Services\Charges\FetchChargeInterface;
use Dpay\Stripe\Api\Endpoints;
use Dpay\Data\Charge as DpayCharge;

/**
 * FetchCharge
 * Service to Get Charge from Stripe API
 * 
 * @property string        $id          Charge ID / URL
 * @property DpayCharge    $dpayCharge	Dpay Charge
 * @property StripeCharge  $sCharge
 */
class FetchCharge extends AbstractCrudCharge implements FetchChargeInterface {
	public StripeCharge $sCharge;
	protected DpayCharge $dpayCharge;

/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Process Request
	 * @return bool
	 */
	public function process() : bool
	{
		if (empty($this->id)) {
			return false;
		}
		$charge = new StripeCharge($this->id);
		$this->sCharge  = $this->processCharge($charge);

		if (empty($this->sCharge) || empty($this->sCharge->id)) {
			$this->errorMsg = 'Charge not found';
			return false;
		}
		$this->dpayCharge = $this->getDpayChargeResponseData();
		return true;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	* Return Stripe Charge
	* @param  StripeCharge $data
	* @return StripeCharge|false;
	*/
   protected function processCharge(StripeCharge $data) : StripeCharge|false
   {
		return Endpoints\Charges::fetchById($this->id);
   }
}