<?php namespace Dpay\Logs\Database\Data;
// Lib
use Dpay\Abstracts\Database\MeekroDB\AbstractRecord;

/**
 * Container for ParsedWebhook Events
 * 
 * @property int|null $rid        Record ID
 * @property string   $timestamp  Timestamp
 * @property int      $conbr      Company Number
 * @property string   $eventid
 * @property string   $type       Type
 * @property string   $raw_apieventdata
 * @property string   $raw_parsedeventdata
 */
class ParsedEventRecord extends AbstractRecord {
	public function __construct() {
		$this->timestamp = '';
		$this->conbr = 0;
		$this->eventid = '';
		$this->type = '';
        $this->raw_apieventdata = '';
		$this->raw_parsedeventdata = '';
	}
}