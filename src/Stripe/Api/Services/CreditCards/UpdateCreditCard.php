<?php namespace Dpay\Stripe\Api\Services\CreditCards;
// Stripe API Library
use Stripe\Card as StripeCreditCard;
// Lib
use Dpay\Abstracts\Api\Services\CreditCards\UpdateCreditCardInterface;
use Dpay\Data\CreditCard as DpayCreditCard;
use Dpay\Stripe\Api\Endpoints;
use Dpay\Stripe\Api\Data\CreditCards\CreditCardRequest as CardRequest;

/**
 * UpdateCreditCard
 * Service to Update Credit Card using Stripe API
 * 
 * @property string              $id              Generated Credit Card ID
 * @property DpayCreditCard      $dpayCreditCard  Card Data
 * @property StripeCreditCard    $sCreditCard     Stripe API CreditCard
 */
class UpdateCreditCard extends AbstractCrudCreditCard implements UpdateCreditCardInterface {
	public StripeCreditCard $sCreditCard;
	protected DpayCreditCard $dpayCreditCard;

/* =============================================================
	Interface Contracts
============================================================= */

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Update CreditCard
	 * @param  CardRequest $rqst
	 * @return StripeCreditCard
	 */
	protected function processCreditCard(CardRequest $rqst) : StripeCreditCard {
		$sCard = Endpoints\CreditCards::update($rqst);

		if (empty($sCard->id) === false) {
			return $sCard;
		}
		$this->errorMsg = Endpoints\CreditCards::$errorMsg;
		return $sCard;
	}
}