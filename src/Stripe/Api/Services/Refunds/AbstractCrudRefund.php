<?php namespace Dpay\Stripe\Api\Services\Refunds;
// Stripe API Library
use Stripe\PaymentIntent as StripeCharge;
use Stripe\Refund as StripeRefund;
// Dpay
use Dpay\Abstracts\Api\Services\Refunds\ACrudRefundTraits;
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
	use ACrudRefundTraits;

	const ACTION = 'refund';

	protected string $id;
	public StripeRefund $sRefund;
	protected DpayRefund $dpayRefund;
	protected StripeCharge $sCharge;
	
/* =============================================================
	Inits @see ACrudRefundTraits
============================================================= */
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
	Interface Contracts @see ACrudRefundTraits
============================================================= */
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
			$this->errorMsg = "Unable to " . static::ACTION . " charge {$this->dpayRefund->charge->custid}";
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