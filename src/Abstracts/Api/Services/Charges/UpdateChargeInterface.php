<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Lib
use Dpay\Data\Charge as Charge;

/**
 * Interface for updating a charge
 * 
 * @property string  $id          API Charge ID
 * @property Charge  $dpayCharge  Charge Data
 * @property string  $errorMsg
 */
interface UpdateChargeInterface extends ACrudChargeInterface {

}