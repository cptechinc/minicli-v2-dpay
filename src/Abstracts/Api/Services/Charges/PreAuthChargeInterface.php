<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Dpay
use Dpay\Data\Charge as Charge;

/**
 * Interface for pre-authorizing a charge
 * 
 * @property string  $id          Generated Charge ID
 * @property Charge  $dpayCharge  Charge Data
 * @property string  $errorMsg
 */
interface PreAuthChargeInterface extends ACrudChargeInterface {

}