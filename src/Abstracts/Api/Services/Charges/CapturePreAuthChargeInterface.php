<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Lib
use Dpay\Data\Charge as Charge;

/**
 * CapturePreAuthChargeInterface
 * Interface for capturing a pre-authorized charge
 * 
 * @property string  $id          Generated Charge ID
 * @property Charge  $dpayCharge  Charge Data
 * @property string  $errorMsg
 */
interface CapturePreAuthChargeInterface extends ACrudChargeInterface {

}