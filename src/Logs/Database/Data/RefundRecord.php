<?php namespace Dpay\Logs\Database\Data;
// Lib
use Dpay\Abstracts\Database\MeekroDB\AbstractRecord;

/**
 * RefundRecord
 * 
 * Container for Refund Log Record
 * 
 * @property int|null $rid              Record ID
 * @property string   $timestamp        Timestamp
 * @property int      $conbr            Company Number
 * @property string   $custid           Dpay Customer ID
 * @property string   $acustid          API Customer ID
 * @property string   $refundid         API Refund ID
 * @property string   $transactionid    API Transaction ID to Refund
 * @property float    $amount           Amount to be charged
 * @property string   $status           Refund Status
 */
class RefundRecord extends AbstractRecord {
	public function __construct() {
		$this->timestamp = '';
		$this->conbr = 0;
		$this->custid = '';
		$this->acustid = '';
		$this->refundid = '';
		$this->transactionid = '';
		$this->amount = 0.00;
		$this->status = '';
	}
}