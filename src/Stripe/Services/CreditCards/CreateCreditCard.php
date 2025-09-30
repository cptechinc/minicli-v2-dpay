<?php namespace Dpay\Stripe\Services\CreditCards;
// Stripe API Library
use Stripe\Card as StripeCreditCard;
// Lib
use Dpay\Abstracts\Api\Services\CreditCards\CreateCreditCardInterface;
use Dpay\Data\CreditCard as DpayCreditCard;
use Dpay\Stripe\Endpoints;
use Dpay\Stripe\Data\CreditCards\CreditCardRequest as CardRequest;

/**
 * CreateCreditCard
 * Service to Create Credit Card using Stripe API
 * 
 * @property string              $id              Generated Credit Card ID
 * @property DpayCreditCard      $dpayCreditCard  Card Data
 * @property StripeCreditCard    $sCreditCard     Stripe API CreditCard
 */
class CreateCreditCard extends AbstractCrudCreditCard implements CreateCreditCardInterface {
	public StripeCreditCard $sCreditCard;
	protected DpayCreditCard $dpayCreditCard;

/* =============================================================
	Interface Contracts
============================================================= */

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Create Stripe Credit Card
	 * @param  CardRequest $rqst
	 * @return StripeCreditCard|false
	 */
	protected function processCreditCard(CardRequest $rqst) : StripeCreditCard|false
	{
		$sCard = Endpoints\CreditCards::create($rqst);

		if (empty($sCard->id) === false) {
			return $sCard;
		}
		$this->errorMsg = Endpoints\CreditCards::$errorMsg;
		return false;
	}
}