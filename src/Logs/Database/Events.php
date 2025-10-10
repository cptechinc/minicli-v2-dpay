<?php namespace Dpay\Logs\Database;
// Pauldro Minicli
use Pauldro\Minicli\v2\Database\MeekroDB\Record as AbstractRecord;
// Lib
use Dpay\Logs\Database\Data\EventRecord as Record;
use Dpay\Abstracts\Database\MeekroDB\AbstractDatabaseTable;

/**
 * Handles Logging Payment Links to Database Table
 * 
 * @method bool    insert(EventRecord $r)  Insert Log Entry
 * @method Record  newRecord()                      Return instance of Record Data Class
 */
class Events extends AbstractDatabaseTable {
	const TABLE  = 'app_events';
	const COLUMNS = [
		'rid'		 => ['INT', 'NOT NULL', 'AUTO_INCREMENT'],
		'timestamp'  => ['DATETIME', 'DEFAULT NULL'],
		'conbr'      => ['INT', 'DEFAULT NULL'],
		'eventid'    => ['VARCHAR(100)', 'DEFAULT ""'],
		'type'       => ['VARCHAR(100)', 'DEFAULT ""'],
		'eventrawdata' => ['LONGTEXT', ''],
	];
	const PRIMARYKEY = ['rid'];
	const RECORD_CLASS = '\\Dpay\\Logs\\Database\\Data\\EventRecord';
	protected static $instance;

/* =============================================================
	Creates
============================================================= */
	/**
	 * Insert Record
	 * @param  Record $r
	 * @return bool
	 */
	public function insert(AbstractRecord $r) : bool
	{
		$r->timestamp = date(self::FORMAT_DATETIME);
		$r->conbr = self::$conbr;
		return parent::insert($r);
	}
	
/* =============================================================
	Reads
============================================================= */
	/**
	 * Return Record by Event id
	 * @param  string $id
	 * @return Record|false
	 */
	public function findOneByEventid($id) : Record|false
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql   = "SELECT * FROM $table WHERE eventid=%s AND conbr=%i";
		$result = $this->db->queryFirstRow($sql, $id, $conbr);

		if (empty($result)) {
			return false;
		}
		$r = $this->newRecord();
		$r->setArray($result);
		return $r;
	}
}