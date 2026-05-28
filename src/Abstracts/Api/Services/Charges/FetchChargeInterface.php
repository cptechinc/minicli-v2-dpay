<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Dpay
use Dpay\Data\Charge;

/**
 * Interface for fetching a Charge
 * 
 * @property string  $id          API Charge ID
 * @property Charge  $dpayCharge
 * @property string  $errorMsg
 */
interface FetchChargeInterface extends ACrudChargeInterface {

}