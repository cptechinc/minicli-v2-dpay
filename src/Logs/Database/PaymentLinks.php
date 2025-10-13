<?php namespace Dpay\Logs\Database;
// Pauldro Minicli
use Pauldro\Minicli\v2\Database\MeekroDB\Record as AbstractRecord;
use Pauldro\Minicli\v2\Database\MeekroDB\RecordList;
// Lib
use Dpay\Logs\Database\Data\PaymentLinkRecord as Record;
use Dpay\Abstracts\Database\MeekroDB\AbstractDatabaseTable;

/**
 * Handles Logging Payment Links to Database Table
 * 
 * @method bool    insert(PaymentLink\LogEntry $r)  Insert Log Entry
 * @method Record  newRecord()                      Return instance of Record Data Class
 */
class PaymentLinks extends AbstractDatabaseTable {
	const TABLE  = 'app_paymentlinks';
	const COLUMNS = [
		'rid'		 => ['INT', 'NOT NULL', 'AUTO_INCREMENT'],
		'timestamp'  => ['DATETIME', 'DEFAULT NULL'],
		'conbr'      => ['INT', 'DEFAULT NULL'],
		'ordernbr'   => ['CHAR(10)', 'DEFAULT NULL'],
		'custid'     => ['CHAR(10)', 'DEFAULT ""'],
		'amount'     => ['DECIMAL(9,2)', 'DEFAULT 0.00'],
		'linkid'     => ['VARCHAR(100)', 'DEFAULT NULL'],
		'url'        => ['VARCHAR(100)', 'DEFAULT NULL'],
		'description' => ['VARCHAR(100)', 'DEFAULT NULL'],
	];
	const PRIMARYKEY = ['rid'];
	const RECORD_CLASS = '\\Dpay\\Logs\\Database\\Data\\PaymentLinkRecord';

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
	 * Return Record by Link id
	 * @param  string $id
	 * @return Record|false
	 */
	public function findOneByLinkid($id) : Record|false
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql   = "SELECT * FROM $table WHERE linkid=%s AND conbr=%i";
		$result = $this->db->queryFirstRow($sql, $id, $conbr);

		if (empty($result)) {
			return false;
		}
		$r = $this->newRecord();
		$r->setArray($result);
		return $r;
	}

	/**
	 * Return of PaymentLink Records
	 * @param  string $id
	 * @return RecordList
	 */
	public function findByLinkid($id) 
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql   = "SELECT * FROM $table WHERE linkid=%s AND conbr=%i";
		$results = $this->db->query($sql, $id, $conbr);
		$list = new RecordList();

		if (empty($results)) {
			return $list;
		}
		foreach ($results as $row) {
			$link = new Record();
			$link->setArray($row);
			$list->add($link);
		}
		return $list;
	}

	/**
	 * Return Record by Sales Order Number
	 * NOTE: returns latest 
	 * @param  string $id
	 * @return Record|false
	 */
	public function findOneByOrdernbr($id) : Record|false
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql   = "SELECT * FROM $table WHERE ordernbr=%s AND conbr=%i ORDER BY timestamp DESC";
		$result = $this->db->queryFirstRow($sql, $id, $conbr);

		if (empty($result)) {
			return false;
		}
		$r = $this->newRecord();
		$r->setArray($result);
		return $r;
	}
}