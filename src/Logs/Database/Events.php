<?php namespace Dpay\Logs\Database;

// Pauldro Minicli
use Pauldro\Minicli\v2\Database\MeekroDB\Record as AbstractRecord;
// Dpay
use Dpay\Logs\Database\Data\EventRecord as Record;
use Dpay\Abstracts\Database\MeekroDB\AbstractDatabaseTable;

/**
 * Handles Logging Webhook events to database
 *
 * @method bool    insert(Record $r)  Insert Log Entry
 * @method Record  newRecord()        Return instance of Record Data Class
 */
class Events extends AbstractDatabaseTable
{
    public $db;
    public const TABLE  = 'app_events';
    public const COLUMNS = [
        'rid'		 => ['INT', 'NOT NULL', 'AUTO_INCREMENT'],
        'timestamp'  => ['DATETIME', 'DEFAULT NULL'],
        'conbr'      => ['INT', 'DEFAULT NULL'],
        'eventid'    => ['VARCHAR(100)', 'DEFAULT ""'],
        'type'       => ['VARCHAR(100)', 'DEFAULT ""'],
        'raw_eventdata' => ['LONGTEXT', ''],
    ];
    public const PRIMARYKEY = ['rid'];
    public const RECORD_CLASS = Record::class;
    #[\Override]
    protected static $instance;

    /* =============================================================
        Creates
    ============================================================= */
    /**
     * Insert Record
     * @param  Record $r
     */
    public function insert(AbstractRecord $r): bool
    {
        $r->timestamp = date(self::FORMAT_DATETIME);
        $r->conbr = self::$conbr;
        return parent::insert($r);
    }

    /* =============================================================
            Reads
        ============================================================= */
    /**
     * Return if Event exists
     * @param  string $id
     */
    public function existsByEventid($id): bool
    {
        $table = static::TABLE;
        $conbr = self::$conbr;
        $sql   = "SELECT * FROM $table WHERE eventid=%s AND conbr=%i";
        return boolval($this->db->queryFirstColumn($sql, $id, $conbr));
    }

    /**
     * Return Record by Event id
     * @param  string $id
     */
    public function findOneByEventid($id): Record|false
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
