<?php namespace Dpay\Abstracts\Api\Services\PaymentLinks;
// Lib
use Dpay\Data\PaymentLink as DpayPaymentLink;


/**
 * PaymentLinkInterface
 * Interface for fetching a Single Payment Link
 * 
 * @property string 			$id               Generated Payment Link ID
 * @property string 			$url	          Generated Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  Dpay PaymentLink Data
 * @property string             $errorMsg
 */
interface FetchPaymentLinkInterface extends ACrudPaymentLinkInterface {

}