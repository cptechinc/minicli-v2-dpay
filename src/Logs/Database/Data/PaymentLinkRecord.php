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
 * @property string   $description
 * @property int	  $isActive
 * @property int	  $isPaid
 */
class PaymentLinkRecord extends AbstractRecord {
	const DEFAULT_VALUES = [
		'timestamp' => '',
		'conbr' 	=> 0,
		'ordernbr'	=> '',
		'custid'	=> '',
		'url'		=> '',
		'linkid'	=> '',
		'description' => '',
		'isActive'    => false,
		'isPaid'      => false
	];

	public function __construct() {
		foreach (self::DEFAULT_VALUES as $field => $value) {
			if ($this->has($field)) {
				continue;
			}
			$this->set($field, $value);
		}
		$this->isActive = boolval($this->isActive);
		$this->isPaid   = boolval($this->isPaid);
	}
}