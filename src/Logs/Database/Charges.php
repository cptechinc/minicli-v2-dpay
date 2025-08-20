<?php namespace Dpay\Logs\Database;
// Pauldro Minicli
use Pauldro\Minicli\v2\Database\MeekroDB\Record as AbstractRecord;
// Lib
use Dpay\Logs\Database\Data\ChargeRecord as Record;
use Dpay\Abstracts\Database\MeekroDB\AbstractDatabaseTable;

/**
 * Charges
 * Handles Logging Charges to Database Table
 * 
 * @method bool    insert(Record $r)  Insert Log Entry
 * @method Record  newRecord()                      Return instance of Record Data Class
 */
class Charges extends AbstractDatabaseTable {
	const TABLE  = 'app_charges';
	const COLUMNS = [
		'rid'		 => ['INT', 'NOT NULL', 'AUTO_INCREMENT'],
		'timestamp'  => ['DATETIME', 'DEFAULT NULL'],
		'conbr'      => ['INT', 'DEFAULT NULL'],
		'custid'     => ['CHAR(10)', 'DEFAULT ""'],
		'acustid'    => ['VARCHAR(100)', 'DEFAULT ""'],
		'ordernbr'   => ['CHAR(10)', 'DEFAULT ""'],
		'transactionid' => ['VARCHAR(100)', 'DEFAULT NULL'],
		'transactiontype' => ['VARCHAR(100)', 'DEFAULT NULL'],
		'amount'          => ['DECIMAL(9,2)', 'DEFAULT 0.00'],
		'status'          => ['VARCHAR(45)', 'DEFAULT ""'],
		'action'          => ['VARCHAR(45)', 'DEFAULT ""'],
	];
	const PRIMARYKEY = ['rid'];
	const RECORD_CLASS = '\\Dpay\\Logs\\\\Database\\Data\\ChargeRecord';

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
	 * Return Record by Api ID
	 * @param  string $id
	 * @return Record|false
	 */
	public function findOneByTransactionid($id) : Record|false
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql = "SELECT * FROM $table WHERE transactionid=%s AND conbr=%i ORDER BY timestamp DESC";
		$result = $this->db->queryFirstRow($sql, $id, $conbr);

		if (empty($result)) {
			return false;
		}
		$r = $this->newRecord();
		$r->setArray($result);
		return $r;
	}

	/**
	 * Delete Record with ID
	 * @param  mixed $id
	 * @return bool
	 */
	public function deleteByTransactionid($id) : bool
	{
		$this->db->delete(static::TABLE, ['transactionid' => $id]);
		return $this->db->affectedRows() > 0;
	}
}