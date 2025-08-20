<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Lib
use Dpay\Data\Charge as Charge;

/**
 * UpdateInterface
 * Interface for updating a charge
 * 
 * @property string  $id          Generated Charge ID
 * @property Charge  $dpayCharge  Charge Data
 * @property string  $errorMsg
 */
interface UpdateChargeInterface extends ACrudChargeInterface {

}