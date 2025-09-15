<?php namespace Dpay\Logs\Database\Data;
// Lib
use Dpay\Abstracts\Database\MeekroDB\AbstractRecord;

/**
 * Container for PaymentLink Record
 * 
 * @property int|null $rid        Record ID
 * @property string   $timestamp  Timestamp
 * @property int      $conbr      Company Number
 * @property string   $custid     Customer ID
 * @property string   $ordernbr   Order Number
 * @property string   $linkid     Link ID
 * @property string   $url        Link URL
 */
class PaymentLinkRecord extends AbstractRecord {
	public function __construct() {
		$this->timestamp = '';
		$this->conbr  = 0;
		$this->ordernbr = '';
		$this->custid = '';
		$this->url    = '';
		$this->linkid = '';
	}
}