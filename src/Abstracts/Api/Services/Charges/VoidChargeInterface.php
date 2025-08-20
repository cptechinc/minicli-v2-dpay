<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Lib
use Dpay\Data\Charge as Charge;

/**
 * VoidInterface
 * Interface for voiding / canceling a charge
 * 
 * @property string  $id          Generated Charge ID
 * @property Charge  $dpayCharge  Charge Data
 * @property string  $errorMsg
 */
interface VoidChargeInterface extends ACrudChargeInterface {

}