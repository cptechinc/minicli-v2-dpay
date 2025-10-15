<?php namespace Dpay\Logs\Database;
// Pauldro Minicli
use Pauldro\Minicli\v2\Database\MeekroDB\Record as AbstractRecord;
// Lib
use Dpay\Logs\Database\Data\PaymentLinkStatusRecord  as Record;
use Dpay\Abstracts\Database\MeekroDB\AbstractDatabaseTable;

/**
 * Handles Logging Payment Links to Database Table
 * 
 * @method bool    insert(Record $r)  Insert Log Entry
 * @method Record  newRecord()                      Return instance of Record Data Class
 */
class PaymentLinkStatus extends AbstractDatabaseTable {
    const TABLE  = 'app_paymentlink_status';
    const COLUMNS = [
        'rid'		    => ['INT', 'NOT NULL', 'AUTO_INCREMENT'],
        'created'       => ['DATETIME', 'DEFAULT NULL'],
        'updated'       => ['DATETIME', 'DEFAULT NULL'],
        'conbr'         => ['INT', 'DEFAULT NULL'],
        'linkid'        => ['VARCHAR(100)', 'DEFAULT NULL'],
        'url'           => ['VARCHAR(100)', 'DEFAULT NULL'],
        'transactionid' => ['VARCHAR(100)', 'DEFAULT NULL'],
        'amount'        => ['DECIMAL(9,2)', 'DEFAULT 0.00'],
        'description'   => ['VARCHAR(100)', 'DEFAULT NULL'],
        'paymentstatus' => ['VARCHAR(20)', 'DEFAULT "unpaid"'],
        'isActive'      => ['INT(1)', 'DEFAULT 1'],
        'isComplete'    => ['INT(1)', 'DEFAULT 0'],
        'errorCode'     => ['VARCHAR(30)', 'DEFAULT ""'],
        'errorMsg'      => ['VARCHAR(100)', 'DEFAULT ""'],
        'authCode'      => ['VARCHAR(30)', 'DEFAULT ""'],
        'raw_metadata'  => ['LONGTEXT', ''],
    ];
    const PRIMARYKEY = ['rid'];
    const RECORD_CLASS = '\\Dpay\\Logs\\Database\\Data\\PaymentLinkStatusRecord';

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
        $r->created      = date(self::FORMAT_DATETIME);
        $r->updated      = date(self::FORMAT_DATETIME);
        $r->conbr        = self::$conbr;
        $r->isActive     = intval($r->isActive);
        $r->isComplete   = intval($r->isComplete);
        $r->raw_metadata = json_encode($r->metadata->getArray());
        return parent::insert($r);
    }

    /**
     * Update Record
     * @param  Record $r
     * @return bool
     */
    public function update(AbstractRecord $r) : bool
    {
        $r->updated      = date(self::FORMAT_DATETIME);
        $r->isActive     = intval($r->isActive);
        $r->isComplete   = intval($r->isComplete);
        $r->raw_metadata = json_encode($r->metadata->getArray());
        return parent::update($r);
    }

    /**
	 * Insert / Update Record
	 * @param  Record $r
	 * @return bool
	 */
	public function insertOrUpdate(AbstractRecord $r) : bool
	{
		if ($r->has('rid')) {
			return $this->update($r);
		}
		return $this->insert($r);
	}
    
/* =============================================================
    Reads
============================================================= */
    /**
     * Return Last Insert ID
     * @return int
     */
    public function lastInsertId() : mixed
    {
        return intval($this->db->insertId());
    }
    
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
}