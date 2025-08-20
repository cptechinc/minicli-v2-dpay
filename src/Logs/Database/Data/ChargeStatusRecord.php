<?php namespace Dpay\Logs\Database\Data;
// Lib
use Dpay\Abstracts\Database\MeekroDB\AbstractRecord;

/**
 * ChargeStatusRecord
 * 
 * Container for Charge Status Log Record
 * 
 * @property int|null $rid              Record ID
 * @property string   $transactionid    API Transaction ID
 * @property string   $createdtimestamp Created Timestamp
 * @property string   $timestamp        Updated Timestamp
 * @property int      $conbr            Company Number
 * @property string   $custid           Dpay Customer ID
 * @property string   $acustid          API Customer ID
 * @property string   $ordernbr         Dpay Order Number
 * @property float    $amount           Amount to be charged
 * @property string   $status           Charge Status
 */
class ChargeStatusRecord extends AbstractRecord {
	public function __construct() {
		$this->timestamp = '';
		$this->conbr = 0;
		$this->custid = '';
		$this->acustid = '';
		$this->ordernbr = '';
		$this->transactionid = '';
		$this->amount = 0.00;
		$this->status = '';
	}
}