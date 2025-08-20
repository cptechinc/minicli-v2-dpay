<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Lib
use Dpay\Data\Charge as ChargeData;

/**
 * FetchChargeInterface
 * Interface for fetching a Charge
 * 
 * @property string      $id       API Charge ID
 * @property ChargeData  $dpayCharge
 * @property string      $errorMsg
 */
interface FetchChargeInterface extends ACrudChargeInterface {

}