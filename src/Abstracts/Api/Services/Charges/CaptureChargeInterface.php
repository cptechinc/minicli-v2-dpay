<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Dpay
use Dpay\Data\Charge as Charge;

/**
 * Interface for capturing a charge
 * 
 * @property string  $id          API Charge ID (Generated)
 * @property Charge  $dpayCharge  Charge Data
 * @property string  $errorMsg
 */
interface CaptureChargeInterface extends ACrudChargeInterface {

}