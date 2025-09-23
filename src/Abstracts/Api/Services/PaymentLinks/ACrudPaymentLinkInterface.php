<?php namespace Dpay\Abstracts\Api\Services\PaymentLinks;
// Dpay
use Dpay\Abstracts\Api\AbstractServiceInterface;
use Dpay\Data\PaymentLink as DpayPaymentLink;

/**
 * ACrudPaymentLinkInterface
 * Template interface for PaymentLink CRUD operations
 * 
 * @property string 			$id               Generated Payment Link ID
 * @property string 			$url	          Generated Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  Dpay PaymentLink Data
 * @property string             $errorMsg
 */
interface ACrudPaymentLinkInterface extends AbstractServiceInterface {

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set DpayPaymentLink
	 * @param  DpayPaymentLink $link
	 * @return void
	 */
	public function setDpayPaymentLink(DpayPaymentLink $link) : void;

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return generated Payment Link URL
	 * @return string
	 */
	public function getUrl() : string;

	/**
	 * Return Generated Payment Link ID
	 * @return string
	 */
	public function getId() : string;

	/**
	 * Return if Error has occurred
	 * @return bool
	 */
	public function hasError() : bool;
	
	/**
	 * Return Response data as Dpay Payment Link
	 * @return DpayPaymentLink
	 */
	public function getDpayPaymentLinkResponseData() : DpayPaymentLink;
}