<?php namespace Dpay\Stripe\Services\Events;
// Stripe API Library
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Event as StripeEvent;
use Stripe\PaymentIntent as StripePaymentIntent;
// Dpay
use Dpay\Data\Event as DpayEvent;
use Dpay\Data\Payment as DpayPayment;
use Dpay\Stripe\Endpoints;
use Dpay\Stripe\Services\AbstractService;
use Dpay\Stripe\Services\PaymentLinks\Util\PaymentMethod;


/**
 * CreateEvent
 * Service to Create Event using Stripe API
 * 
 * @property string 	     $id			API Event ID
 * @property DpayEvent		 $dpayEvent	Dpay Event
 * @property StripeEvent 	 $sEvent 	Stripe API Event
 */
class FetchEvent extends AbstractService {
	
	const ACTION_DESCRIPTION = 'fetch';

	protected string $id;
	public StripeEvent $sEvent;
	public DpayEvent $dpayEvent;

/* =============================================================
	Interface Contracts
============================================================= */
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
	 * Return API Event ID
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
		if (empty($this->id)) {
            $this->errorMsg = "Event ID not set";
			return false;
		}

        $this->sEvent = Endpoints\Events::fetchById($this->id);
        
		if (empty($this->sEvent) || empty($this->sEvent->id)) {
			if ($this->errorMsg) {
				return false;
			}
			$this->errorMsg = "Unable to Fetch Event {$this->id}";
			return false;
		}
		$this->dpayEvent = $this->getDpayEventResponseData();
		echo json_encode($this->dpayEvent->getArray());
		return true;
	}

	/**
	 * Return Response data as Dpay Event
	 * @return DpayEvent
	 */
	public function getDpayEventResponseData() : DpayEvent
	{
		if ($this->sEvent->type == 'checkout.session.async_payment_succeeded') {
			return $this->getDpayEventCheckoutResponseData();
		}
		if ($this->sEvent->type == 'checkout.session.async_payment_failed') {
			return $this->getDpayEventCheckoutResponseData();
		}
		return new DpayEvent();
	}

	private function getDpayEventCheckoutResponseData() : DpayEvent
	{	
		/** @var StripeCheckoutSession */
		$checkout      = $this->sEvent->data->object;
		$data          = new DpayEvent();
		$data->id      = $this->sEvent->id;
		$data->type    = 'payment';
		$data->apitype = $this->sEvent->type;
		$data->timestamp = $this->sEvent->created;

		$data->object = new DpayPayment();
		$data->object->id       = $checkout->payment_link;
		$data->object->transactionid = $checkout->payment_intent;
		$data->object->type     = 'paymentlink';
		$data->object->custid   = $checkout->metadata->offsetExists('custid') ? $checkout->metadata->custid : '';
		$data->object->ordernbr = $checkout->metadata->offsetExists('ordernbr') ? $checkout->metadata->ordernbr : '';
		$data->object->method   = PaymentMethod::findDpayMethod($checkout->payment_method_types[0])->value;
		$data->object->status   = $checkout->payment_status;
		$data->object->amount   = $checkout->amount_total / 100;
		$data->object->success  = $checkout->payment_status = 'paid';
		
		foreach ($checkout->metadata->toArray() as $key => $value) {
			$data->object->metadata->set($key, $value);
		}
		return $data;
	}

/* =============================================================
	Internal Processing
============================================================= */
}