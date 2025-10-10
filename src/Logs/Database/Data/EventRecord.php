<?php namespace Dpay\Logs\Database\Data;
// Lib
use Dpay\Abstracts\Database\MeekroDB\AbstractRecord;

/**
 * Container for Webhook Events
 * 
 * @property int|null $rid        Record ID
 * @property string   $timestamp  Timestamp
 * @property int      $conbr      Company Number
 * @property string   $eventid
 * @property string   $type       Type
 * @property string   $eventrawdata
 */
class EventRecord extends AbstractRecord {
	public function __construct() {
		$this->timestamp = '';
		$this->conbr = 0;
		$this->eventid = '';
		$this->type = '';
        $this->eventrawdata = '';
	}
}