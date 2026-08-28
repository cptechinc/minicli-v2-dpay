<?php namespace Dpay\Abstracts\Api\Services\PaymentLinks;

// Dpay
use Dpay\Data\PaymentLink as DpayPaymentLink;

/**
 * Interface for fetching a Single Payment Link
 *
 * @property string 			$id               API Payment Link ID
 * @property string 			$url	          Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  Dpay PaymentLink Data
 * @property string             $errorMsg
 */
interface FetchPaymentLinkInterface extends ACrudPaymentLinkInterface
{
}
