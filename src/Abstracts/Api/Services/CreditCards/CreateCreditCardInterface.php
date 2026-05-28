<?php namespace Dpay\Abstracts\Api\Services\CreditCards;
// Dpay
use Dpay\Data\CreditCard as CreditCard;

/**
 * Template class for generating CreditCard
 * 
 * @property string      $id              API CreditCard ID (Generated)
 * @property CreditCard  $dpayCreditCard  CreditCard Data
 * @property string      $errorMsg
 */
interface CreateCreditCardInterface extends ACrudCreditCardInterface {

}