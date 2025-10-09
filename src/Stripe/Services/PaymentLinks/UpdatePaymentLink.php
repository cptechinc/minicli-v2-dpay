<?php namespace Dpay\Stripe\Services\PaymentLinks;
// Stripe API Library
use Stripe\PaymentLink as StripePaymentLink;
// Lib
use Dpay\Abstracts\Api\Services\PaymentLinks\CreatePaymentLinkInterface;
use Dpay\Data\PaymentLink as DpayPaymentLink;
use Dpay\Stripe\Endpoints;
use Dpay\Stripe\Data\PaymentLinks\PaymentLinkRequest; 

/**
 * UpdatePaymentLink
 * Service to Update Payment Link to Stripe API
 * 
 * @property string 			$id               Generated Payment Link ID
 * @property string 			$url	          Generated Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  PaymentLink Data
 * @property StripePaymentLink  $sPaymentLink     Stripe Payment Link
 * @property string             $errorMsg
 */
class UpdatePaymentLink extends CreatePaymentLink implements CreatePaymentLinkInterface {
	const ACTION = 'update';
	public StripePaymentLink $sPaymentLink;
	protected DpayPaymentLink $dpayPaymentLink;

/* =============================================================
	Internal Processing
============================================================= */
	 /**
     * Creates Stripe PaymentLink
     * @param  PaymentLinkRequest $data
     * @return StripePaymentLink
     */
	protected function processPaymentLink(PaymentLinkRequest $data) : StripePaymentLink
	{
		return $this->updatePaymentLink($data);
	}

	/**
	 * Return Payment Link Request
	 * @param  DpayPaymentLink $link
	 * @return PaymentLinkRequest
	 */
	protected function generatePaymentLinkRequest(DpayPaymentLink $link) : PaymentLinkRequest
	{
		$data = new PaymentLinkRequest();
		$data->id = $link->id;
		$data->isActive = $link->isActive;
		$data->items = $this->generateLineItemsList($link);
		$data->paymentMethodTypes = $this->getEnvAllowedPaymentTypes();

		if ($link->order->ordernbr) {
			$data->metadata->custid   = $link->order->custid;
			$data->metadata->ordernbr = $link->order->ordernbr;
		}
		
		foreach ($link->metadata as $key => $value) {
			$data->metadata->set($key, $value);
		}
		if ($link->description) {
			$data->metadata->set('description', $link->description);
		}
		if ($link->redirectUrl) {
			$data->redirectUrl = $link->redirectUrl;
		}
		return $data;
	}
	
	/**
	 * Update Payment Link
	 * @param  PaymentLinkRequest $rqst
	 * @return StripePaymentLink
	 */
	protected function updatePaymentLink(PaymentLinkRequest $rqst) : StripePaymentLink
	{
		$link = Endpoints\PaymentLinks::update($rqst);

		if (empty($link->id) === false) {
			return $link;
		}
		$this->errorMsg = Endpoints\PaymentLinks::$errorMsg;
		return $link;
	}
}