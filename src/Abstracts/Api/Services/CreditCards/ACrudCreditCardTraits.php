<?php namespace Dpay\Abstracts\Api\Services\CreditCards;
// Dpay
use Dpay\Data\CreditCard as DpayCreditCard;

trait ACrudCreditCardTraits {
/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Set Dpay Credit Card
	 * @param  DpayCreditCard $card
	 * @return void
	 */
	public function setDpayCreditCard(DpayCreditCard $dpayCreditCard) : void
	{
		$this->dpayCreditCard = $dpayCreditCard;
	}

	/**
	 * Return Dpay Credit Card
	 * @return DpayCreditCard
	 */
	public function getDpayCreditCard() : DpayCreditCard
	{
		return $this->dpayCreditCard;
	}

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
	 * Return API Credit Card ID
	 * @return string
	 */
	public function getId() : string
	{
		return $this->id;
	}
}