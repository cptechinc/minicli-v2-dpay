<?php namespace Dpay\Abstracts\Database\MeekroDB;

// Pauldro Minicli
use Pauldro\Minicli\v2\Database\MeekroDB\AbstractTable as ParentTable;

/**
 * Template for App Database tables
 */
class AbstractDatabaseTable extends ParentTable
{
    public const SESSION_CONNECTION_NAME = 'app';
    public const INSTALL_DB = true;

    protected static int $conbr;

    public static function setConbr(int $conbr): void
    {
        self::$conbr = $conbr;
    }

    public static function getConbr(): int
    {
        return static::$conbr;
    }
}
