<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Lib
use Dpay\Data\Charge as Charge;

/**
 * PreAuthInterface
 * Interface for pre-authorizing a charge
 * 
 * @property string  $id          Generated Charge ID
 * @property Charge  $dpayCharge  Charge Data
 * @property string  $errorMsg
 */
interface PreAuthChargeInterface extends ACrudChargeInterface {

}