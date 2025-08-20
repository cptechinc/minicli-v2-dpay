<?php namespace Dpay\Logs\Database\Data;
// Lib
use Dpay\Abstracts\Database\MeekroDB\AbstractRecord;

/**
 * CreditCardRecord
 * 
 * Container for CreditCard Record
 * 
 * @property int|null $rid        Record ID
 * @property string   $timestamp  Timestamp
 * @property int      $conbr      Company Number
 * @property string   $custid     Customer ID
 * @property string   $acustid    API Cust ID
 * @property string   $cardid     Card ID
 * @property string   $issuer     Card Issuer
 * @property string   $last4      Last 4 Card
 */
class CreditCardRecord extends AbstractRecord {
	public function __construct() {
		$this->timestamp = '';
		$this->conbr  = 0;
		$this->custid = '';
		$this->acustid = '';
		$this->cardid  = '';
		$this->issuer  = '';
		$this->last4   = '';
	}
}