<?php namespace Dpay\Logs\Database\Data;
// Lib
use Dpay\Abstracts\Database\MeekroDB\AbstractRecord;

/**
 * CustomerRecord
 * Container for Customer data
 * 
 * @property int|null $rid        Record ID
 * @property string   $timestamp  Timestamp
 * @property int      $conbr      Company Number
 * @property string   $custid     Dpay Cust ID
 * @property string   $id         API ID
 */
class CustomerRecord extends AbstractRecord {
	public function __construct() {
		$this->timestamp = '';
		$this->conbr = 0;
		$this->custid = '';
		$this->id = '';
	}
}