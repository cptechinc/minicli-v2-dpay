<?php namespace Dpay\Abstracts\Api\Services\CreditCards;
// Lib
use Dpay\Abstracts\Api\AbstractServiceInterface;
use Dpay\Data\CreditCard as CreditCard;

/**
 * ACrudCreditCardInterface
 * Template class for CreditCard CRUD operations
 * 
 * @property string      $id              Generated CreditCard ID
 * @property CreditCard  $dpayCreditCard  CreditCard Data
 * @property string      $errorMsg
 */
interface ACrudCreditCardInterface extends AbstractServiceInterface {

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set CreditCard
	 * @param  CreditCard $card
	 * @return void
	 */
	public function setDpayCreditCard(CreditCard $card) : void;

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return Generated CreditCard ID
	 * @return string
	 */
	public function getId() : string;

	/**
	 * Return if Error has occurred
	 * @return bool
	 */
	public function hasError() : bool;
	
	/**
	 * Return Response data as Dpay Credit Card
	 * @return CreditCard
	 */
	public function getDpayCreditCardResponseData() : CreditCard;
}