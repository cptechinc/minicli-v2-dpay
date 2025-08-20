<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Lib
use Dpay\Data\Charge as Charge;

/**
 * ConfirmChargeInterface
 * Interface for confirming a charge
 * 
 * @property string  $id          Generated Charge ID
 * @property Charge  $dpayCharge  Charge Data
 * @property string  $errorMsg
 */
interface ConfirmChargeInterface extends ACrudChargeInterface {

}