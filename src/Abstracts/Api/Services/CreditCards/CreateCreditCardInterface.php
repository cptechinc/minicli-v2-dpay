<?php namespace Dpay\Abstracts\Api\Services\CreditCards;
// Lib
use Dpay\Data\CreditCard as CreditCard;

/**
 * AbstractCreateCreditInterface
 * Template class for generating CreditCard
 * 
 * @property string      $id              Generated CreditCard ID
 * @property CreditCard  $dpayCreditCard  CreditCard Data
 * @property string      $errorMsg
 */
interface CreateCreditCardInterface extends ACrudCreditCardInterface {

}