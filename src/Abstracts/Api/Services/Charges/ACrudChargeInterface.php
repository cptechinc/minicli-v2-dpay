<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Lib
use Dpay\Abstracts\Api\AbstractServiceInterface;
use Dpay\Data\Charge as Charge;

/**
 * ACrudChargeInterface
 * Template class for Charge CRUD operations
 * 
 * @property string  $id          Generated Charge ID
 * @property Charge  $dpayCharge  Charge Data
 * @property string  $errorMsg
 */
interface ACrudChargeInterface extends AbstractServiceInterface {

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set Charge
	 * @param  Charge $card
	 * @return void
	 */
	public function setDpayCharge(Charge $card) : void;

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return Generated Charge ID
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
	 * @return Charge
	 */
	public function getDpayChargeResponseData() : Charge;
}