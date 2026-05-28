<?php namespace Dpay\Abstracts\Api\Services\PaymentLinks;
// Dpay
use Dpay\Data\PaymentLink as DpayPaymentLink;

/**
 * Interface for creating Payment Links
 * 
 * @property string 			$id               API Payment Link ID (Generated)
 * @property string 			$url	          Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  Dpay PaymentLink Data
 * @property string             $errorMsg
 */
interface CreatePaymentLinkInterface extends ACrudPaymentLinkInterface {

}