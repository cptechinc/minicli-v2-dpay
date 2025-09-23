<?php namespace Dpay\Stripe\Api\Services\PaymentLinks;
// Stripe API Library
use Stripe\PaymentLink as StripePaymentLink;
// Lib
use Dpay\Abstracts\Api\Services\PaymentLinks\FetchPaymentLinkInterface;
use Dpay\Stripe\Api\Data\PaymentLinks\PaymentLinkRequest; 
use Dpay\Stripe\Api\Endpoints;
use Dpay\Data\PaymentLink as DpayPaymentLink;


/**
 * FetchPaymentLink
 * Service to fetch PaymentLink from Stripe API
 * 
 * @property string 			$id               Generated Payment Link ID
 * @property string 			$url	          Generated Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  PaymentLink Data
 * @property StripePaymentLink  $sPaymentLink     Stripe Payment Link
 * @property string             $errorMsg
 */
class FetchPaymentLink extends AbstractCrudPaymentLink implements FetchPaymentLinkInterface {
	const ACTION = 'fetch';
	public StripePaymentLink $sPaymentLink;
	protected DpayPaymentLink $dpayPaymentLink;

/* =============================================================
	Inits
============================================================= */
	/**
	 * Init Dpay PaymentLink
	 * @return bool
	 */
	protected function initDpayPaymentLink() : bool
	{
		$this->dpayPaymentLink = new DpayPaymentLink();
		$this->dpayPaymentLink->id = $this->id;
		return true;
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	* Return Stripe PaymentLink
	* @param  PaymentLinkRequest $data
	* @return StripePaymentLink
	*/
   protected function processPaymentLink(PaymentLinkRequest $data) : StripePaymentLink
   {
		return Endpoints\PaymentLinks::fetchById($data->id);
   }
}