<?php namespace Dpay\Abstracts\Api\Services\PaymentLinks;
// Lib
use Dpay\Data\PaymentLink as DpayPaymentLink;

/**
 * CreateDpayPaymentLinkInterface
 * Interface for creating Payment Links
 * 
 * @property string 			$id               Generated Payment Link ID
 * @property string 			$url	          Generated Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  Dpay PaymentLink Data
 * @property string             $errorMsg
 */
interface CreatePaymentLinkInterface extends ACrudPaymentLinkInterface {

}