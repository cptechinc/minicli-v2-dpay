<?php namespace Dpay\Logs\Database\Data;
// Lib
use Dpay\Abstracts\Database\MeekroDB\AbstractRecord;

/**
 * ChargeRecord
 * 
 * Container for Charge Log Record
 * 
 * @property int|null $rid              Record ID
 * @property string   $timestamp        Timestamp
 * @property int      $conbr            Company Number
 * @property string   $custid           Dpay Customer ID
 * @property string   $acustid          API Customer ID
 * @property string   $ordernbr         Dpay Order Number
 * @property string   $transactionid    API Transaction ID
 * @property string   $transactiontype  Transaction Type 
 * @property float    $amount           Amount to be charged
 * @property string   $status           Charge Status
 * @property string   $action           Action Taken
 */
class ChargeRecord extends AbstractRecord {
	public function __construct() {
		$this->timestamp = '';
		$this->conbr = 0;
		$this->custid = '';
		$this->acustid = '';
		$this->ordernbr = '';
		$this->transactionid = '';
		$this->transactiontype = '';
		$this->amount = 0.00;
		$this->status = '';
		$this->action = '';
	}
}