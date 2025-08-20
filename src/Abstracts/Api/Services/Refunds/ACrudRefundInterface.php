<?php namespace Dpay\Abstracts\Api\Services\Refunds;
// Lib
use Dpay\Abstracts\Api\AbstractServiceInterface;
use Dpay\Data\Refund as Refund;

/**
 * ACrudRefundInterface
 * Template class for Refund CRUD operations
 * 
 * @property string  $id          Generated Refund ID
 * @property Refund  $dpayRefund  Refund Data
 * @property string  $errorMsg
 */
interface ACrudRefundInterface extends AbstractServiceInterface {

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set Refund
	 * @param  Refund $card
	 * @return void
	 */
	public function setDpayRefund(Refund $card) : void;

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return Generated Refund ID
	 * @return string
	 */
	public function getId() : string;

	/**
	 * Return if Error has occurred
	 * @return bool
	 */
	public function hasError() : bool;
	
	/**
	 * Return Response data as Dpay Refund
	 * @return Refund
	 */
	public function getDpayRefundResponseData() : Refund;
}