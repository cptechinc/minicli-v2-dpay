<?php namespace Dpay\Logs\Database;
// Pauldro Minicli
use Pauldro\Minicli\v2\Database\MeekroDB\Record as AbstractRecord;
// Lib
use Dpay\Logs\Database\Data\CreditCardRecord as Record;
use Dpay\Abstracts\Database\MeekroDB\AbstractDatabaseTable;

/**
 * CreditCards
 * Handles Logging CreditCards to Database Table
 * 
 * @method bool    insert(Record $r)  Insert Log Entry
 * @method Record  newRecord()                      Return instance of Record Data Class
 */
class CreditCards extends AbstractDatabaseTable {
	const TABLE  = 'app_creditcards';
	const COLUMNS = [
		'rid'		 => ['INT', 'NOT NULL', 'AUTO_INCREMENT'],
		'timestamp'  => ['DATETIME', 'DEFAULT NULL'],
		'conbr'      => ['INT', 'DEFAULT NULL'],
		'custid'     => ['CHAR(10)', 'DEFAULT ""'],
		'acustid'    => ['VARCHAR(100)', 'DEFAULT NULL'],
		'cardid'     => ['VARCHAR(100)', 'DEFAULT NULL'],
		'issuer'     => ['VARCHAR(25)', 'DEFAULT NULL'],
		'last4'      => ['CHAR(4)', 'DEFAULT NULL'],
	];
	const PRIMARYKEY = ['rid'];
	const RECORD_CLASS = '\\Dpay\\Logs\\Database\\Data\\CreditCardRecord';

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
	
	/**
	 * Update Record
	 * @param  Record $r
	 * @return bool
	 */
	public function update(AbstractRecord $r) : bool {
		$r->timestamp = date(self::FORMAT_DATETIME);
		return parent::update($r);
	}
	
/* =============================================================
	Reads
============================================================= */
	/**
	 * Return Record by Card ID
	 * @param  string $id
	 * @return Record|false
	 */
	public function findOneByCardid($id) : Record|false
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql = "SELECT * FROM $table WHERE cardid=%s AND conbr=%i";
		$result = $this->db->queryFirstRow($sql, $id, $conbr);

		if (empty($result)) {
			return false;
		}
		$r = $this->newRecord();
		$r->setArray($result);
		return $r;
	}

	/**
	 * Return Record by Customer ID, Last 4
	 * @param  string $custID
	 * @param  string $last4
	 * @return Record|false
	 */
	public function findOneByCustidLast4($custID, $last4) : Record|false
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql = "SELECT * FROM $table WHERE custid=%s AND last4=%s AND conbr=%i ORDER BY rid DESC";
		$result = $this->db->queryFirstRow($sql, $custID, $last4, $conbr);

		if (empty($result)) {
			return false;
		}
		$r = $this->newRecord();
		$r->setArray($result);
		return $r;
	}

/* =============================================================
	Deletes
============================================================= */
	/**
	 * Return Record by Record ID
	 * @param  int $id Record ID
	 * @return bool
	 */
	public function deleteByRid(int $id) : bool
	{
		$table = static::TABLE;
		$conbr = self::$conbr;
		$sql = "DELETE FROM $table WHERE rid=%i AND conbr=%i";
		$this->db->query($sql, $id, $conbr);
		$counter = $this->db->affectedRows();
		return $counter > 0;
	}
}