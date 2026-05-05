<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Dpay
use Dpay\Data\Charge as ChargeData;

/**
 * Interface for fetching a Charge
 * 
 * @property string      $id       API Charge ID
 * @property ChargeData  $dpayCharge
 * @property string      $errorMsg
 */
interface FetchChargeInterface extends ACrudChargeInterface {

}