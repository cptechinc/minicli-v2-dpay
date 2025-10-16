<?php namespace Dpay\Stripe\Services\Events;
// Stripe API Library
use Stripe\Event as StripeEvent;
// Dpay
use Dpay\Data\Event as DpayEvent;
use Dpay\Stripe\Config;
use Dpay\Stripe\Endpoints;
use Dpay\Stripe\Services\AbstractService;
use Dpay\Stripe\Services\Events\Util\AdefaultEventParser;
use Dpay\Stripe\Services\Events\Util\PaymentLinkEventParser;

/**
 * Fetch
 * Service to Fetch Event data using Stripe API
 * 
 * @property string 	     $id		 API Event ID
 * @property DpayEvent		 $dpayEvent	 Dpay Event
 * @property StripeEvent 	 $sEvent 	 Stripe API Event
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
		return true;
	}

	/**
	 * Return Response data as Dpay Event
	 * @return DpayEvent
	 */
	public function getDpayEventResponseData() : DpayEvent
	{
		$config = Config::instance();

		if ($config->actionableEvents->has($this->sEvent->type)) {
			return AdefaultEventParser::parse($this->sEvent);
		}

		switch ($this->sEvent->type) {
			case 'checkout.session.async_payment_failed':
				return PaymentLinkEventParser::parse($this->sEvent);
			case 'checkout.session.async_payment_succeeded':
				return PaymentLinkEventParser::parse($this->sEvent);
			default:
				return AdefaultEventParser::parse($this->sEvent);
		}
	}

/* =============================================================
	Internal Processing
============================================================= */
}