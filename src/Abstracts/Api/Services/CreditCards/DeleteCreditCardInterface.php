<?php namespace Dpay\Abstracts\Api\Services\CreditCards;
// Dpay
use Dpay\Data\CreditCard as CreditCard;

/**
 * Template class for deleting a CreditCard
 * 
 * @property string      $id              API CreditCard ID
 * @property CreditCard  $dpayCreditCard  CreditCard Data
 * @property string      $errorMsg
 */
interface DeleteCreditCardInterface extends ACrudCreditCardInterface {

}