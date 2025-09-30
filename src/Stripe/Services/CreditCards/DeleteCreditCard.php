<?php namespace Dpay\Stripe\Services\CreditCards;
// Stripe API Library
use Stripe\Card as StripeCreditCard;
// Lib
use Dpay\Abstracts\Api\Services\CreditCards\DeleteCreditCardInterface;
use Dpay\Data\CreditCard as DpayCreditCard;
use Dpay\Stripe\Endpoints;
use Dpay\Stripe\Data\CreditCards\CreditCardRequest as CardRequest;

/**
 * DeleteCreditCard
 * Service to Delete Credit Card using Stripe API
 * 
 * @property string              $id              Generated Credit Card ID
 * @property DpayCreditCard      $dpayCreditCard  Card Data
 * @property StripeCreditCard    $sCreditCard     Stripe API CreditCard
 */
class DeleteCreditCard extends AbstractCrudCreditCard implements DeleteCreditCardInterface {
	public StripeCreditCard $sCreditCard;
	protected DpayCreditCard $dpayCreditCard;

/* =============================================================
	Interface Contracts
============================================================= */

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Delete CreditCard
	 * @param  CardRequest $rqst
	 * @return StripeCreditCard|false
	 */
	protected function processCreditCard(CardRequest $rqst) : StripeCreditCard|false
	{
		$sCard = Endpoints\CreditCards::delete($rqst);
		
		if (empty($sCard->id) || $sCard->isDeleted() === false) {
			$this->errorMsg = Endpoints\CreditCards::$errorMsg;
			return $sCard;
		}
		return $sCard;
	}
}