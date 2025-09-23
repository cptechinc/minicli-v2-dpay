<?php namespace Dpay\Stripe\Api\Services\CreditCards;
// Stripe API Library
use Stripe\Card as StripeCreditCard;
// Dpay
use Dpay\Abstracts\Api\Services\CreditCards\ACrudCreditCardTraits;
use Dpay\Data\CreditCard as DpayCreditCard;
use Dpay\Stripe\Api\AbstractService;
use Dpay\Stripe\Api\Data\CreditCards\CreditCardRequest as CardRequest;

/**
 * AbstractCrudCreditCard
 * Service to Create Credit Card using Stripe API
 * 
 * @property string 			 $id			    API CreditCard ID
 * @property DpayCreditCard		 $dpayCreditCard	Dpay Credit Card (Request)
 * @property StripeCreditCard 	 $sCreditCard 	    Stripe API Credit Card
 */
abstract class AbstractCrudCreditCard extends AbstractService {
	const ACTION_DESCRIPTION = 'create';
	public StripeCreditCard $sCreditCard;
	protected DpayCreditCard $dpayCreditCard;

/* =============================================================
	Interface Contracts @see ACrudCreditCardTraits
============================================================= */
	/**
	 * Process Request
	 * @return bool
	 */
	public function process() : bool
	{
		if (empty($this->dpayCreditCard)) {
			$this->errorMsg = 'Card Data not set';
			return false;
		}
		$rqst   = $this->generateCreditCardRequest($this->dpayCreditCard);
		$sCard = $this->processCreditCard($rqst);

		if (empty($sCard) || empty($sCard->id)) {
			if ($this->errorMsg) {
				return false;
			}
			$this->errorMsg = "Unable to " . static::ACTION_DESCRIPTION . " Credit Card {$this->dpayCreditCard->custid}";
			return false;
		}
		$this->sCreditCard = $sCard;
		$this->id		   = $sCard->id;
		return true;
	}

	/**
	 * Return Response data as Dpay Credit Card
	 * @return DpayCreditCard
	 */
	public function getDpayCreditCardResponseData() : DpayCreditCard
	{
		$sCard = $this->sCreditCard;

		$dpay = new DpayCreditCard();
		$dpay->aid		= $sCard->id;
		$dpay->acustid	= $sCard->customer;
		$dpay->custid	= $sCard->metadata->offsetExists('custid') ? $sCard->metadata->custid : '';
		$dpay->address1 = $sCard->address_line1;
		$dpay->address2 = $sCard->address_line2;
		$dpay->city 	= $sCard->address_city;
		$dpay->state	= $sCard->address_state;
		$dpay->country	= $sCard->address_country;
		$dpay->zipcode	= $sCard->address_zip;
		$dpay->last4	= $sCard->last4;
		$dpay->brand	= $sCard->brand;
		return $dpay;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Generate Credit Card Data
	 * @param  DpayCreditCard $card
	 * @return CardRequest
	 */
	protected function generateCreditCardRequest(DpayCreditCard $card) : CardRequest
	{
		$data = new CardRequest();
		$data->id     = $card->aid;
        $data->custid = $card->acustid;
        $data->name   = $card->name;
        $data->number = $card->cardnbr;
        $data->cvc    = $card->cvc;
        $data->exp_month = $card->expiredateMonth();
        $data->exp_year = $card->expiredateYear();
        $data->address_line1 = $card->address1;
        $data->address_line2 = $card->address2;
        $data->address_city  = $card->city;
        $data->address_state = $card->state;
        $data->address_country = $card->country;
		return $data;
	}

    /**
     * Creates Stripe Credit Card
     * @param  CardRequest $data
     * @return StripeCreditCard|false
     */
	abstract protected function processCreditCard(CardRequest $rqst) : StripeCreditCard|false;
}