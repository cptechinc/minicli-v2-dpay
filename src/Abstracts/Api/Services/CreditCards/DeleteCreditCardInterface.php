<?php namespace Dpay\Abstracts\Api\Services\CreditCards;
// Lib
use Dpay\Data\CreditCard as CreditCard;

/**
 * DeleteCreditCardInterface
 * Template class for deleting a CreditCard
 * 
 * @property string      $id              API CreditCard ID
 * @property CreditCard  $dpayCreditCard  CreditCard Data
 * @property string      $errorMsg
 */
interface DeleteCreditCardInterface extends ACrudCreditCardInterface {

}