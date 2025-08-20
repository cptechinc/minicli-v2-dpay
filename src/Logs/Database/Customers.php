<?php namespace Dpay\Logs\Database;
// Pauldro Minicli
use Pauldro\Minicli\v2\Database\MeekroDB\Record as AbstractRecord;
// Lib
use Dpay\Logs\Database\Data\CustomerRecord as Record;
use Dpay\Abstracts\Database\MeekroDB\AbstractDatabaseTable;

/**
 * Customers
 * Handles Logging Customers to Database Table
 * 
 * @method bool    insert(Record $r)  Insert Log Entry
 * @method Record  newRecord()                      Return instance of Record Data Class
 */
class Customers extends AbstractDatabaseTable {
	const TABLE  = 'app_customers';
	const COLUMNS = [
		'rid'		 => ['INT', 'NOT NULL', 'AUTO_INCREMENT'],
		'timestamp'  => ['DATETIME', 'DEFAULT NULL'],
		'conbr'      => ['INT', 'DEFAULT NULL'],
		'custid'     => ['CHAR(10)', 'DEFAULT ""'],
		'id'         => ['VARCHAR(100)', 'DEFAULT NULL'],
	];
	const PRIMARYKEY = ['rid'];
	const RECORD_CLASS = '\\Dpay\\Logs\\Database\\Data\\CustomerRecord';

	protected static $instance;

/* =============================================================
	Creates
============================================================= */
	/**
	 * Insert Record
	 * @param  Record $r
	 * @return bool
	 */
	public function insert(AbstractRecord $r) : bool {
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
	public function findOneById($id) : Record|false
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql = "SELECT * FROM $table WHERE id=%s AND conbr=%i ORDER BY timestamp DESC";
		$result = $this->db->queryFirstRow($sql, $id, $conbr);

		if (empty($result)) {
			return false;
		}
		$r = $this->newRecord();
		$r->setArray($result);
		return $r;
	}
	/**
	 * Return Record by Cust ID
	 * @param  string $id
	 * @return Record|false
	 */
	public function findOneByCustid($id) : Record|false
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql = "SELECT * FROM $table WHERE custid=%s AND conbr=%i ORDER BY timestamp DESC";
		$result = $this->db->queryFirstRow($sql, $id, $conbr);

		if (empty($result)) {
			return false;
		}
		$r = $this->newRecord();
		$r->setArray($result);
		return $r;
	}

	/**
	 * Return Record by Cust ID
	 * @param  string $id
	 * @return bool
	 */
	public function existsCustid($id) : bool
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql = "SELECT COUNT(*) FROM $table WHERE custid=%s AND conbr=%i";
		$result = $this->db->queryFirstField($sql, $id, $conbr);
		return boolval($result);
	}

	/**
	 * Return API customer ID by Dpay Customer ID
	 * @param  string $custid
	 * @return string
	 */
	public function idByCustid($custid) : string
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql = "SELECT id FROM $table WHERE custid=%s AND conbr=%i ORDER BY timestamp DESC";
		/** @var string */
		$id = $this->db->queryFirstField($sql, $custid, $conbr);
		return empty($id) ? '' : $id;
	}

	/**
	 * Delete Record with ID
	 * @param  mixed $id
	 * @return bool
	 */
	public function deleteById($id) : bool
	{
		$this->db->delete(static::TABLE, ['id' => $id]);
		return $this->db->affectedRows() > 0;
	}
}