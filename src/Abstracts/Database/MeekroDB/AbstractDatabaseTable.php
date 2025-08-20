<?php namespace Dpay\Abstracts\Database\MeekroDB;
// Pauldro Minicli
use Pauldro\Minicli\v2\Database\MeekroDB\AbstractTable as ParentTable;

/**
 * Summary of AbstractTable
 */
class AbstractDatabaseTable extends ParentTable {
    const SESSION_CONNECTION_NAME = 'app';
    
    protected static int $conbr;

    /**
	 * Set Company Number
	 * @param  int $conbr
	 * @return void
	 */
	public static function setConbr(int $conbr) : void {
		self::$conbr = $conbr;
	}

	public static function getConbr() : int {
		return static::$conbr;
	}
}
