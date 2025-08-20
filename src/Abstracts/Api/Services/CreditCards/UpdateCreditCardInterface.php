<?php namespace Dpay\Abstracts\Api\Services\CreditCards;
// Lib
use Dpay\Data\CreditCard as CreditCard;

/**
 * AbstractUpdateCreditInterface
 * Template class for updating CreditCard
 * 
 * @property string      $id              Generated CreditCard ID
 * @property CreditCard  $dpayCreditCard  CreditCard Data
 * @property string      $errorMsg
 */
interface UpdateCreditCardInterface extends ACrudCreditCardInterface {

}