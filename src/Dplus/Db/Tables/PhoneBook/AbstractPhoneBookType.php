<?php namespace Dpay\Dplus\Db\Tables\PhoneBook;

// Dplus Models
use PhoneBookQuery as Query;

/**
 * Template Class for querying phoneadr records from database
 */
class AbstractPhoneBookType extends AbstractPhoneBook
{
    public const TYPE = '';

    #[\Override]
    protected static $instance;

    /* =============================================================
        Query Functions
    ============================================================= */
    /**
     * Return Query Filtered by type
     */
    public function queryType(): Query
    {
        return $this->query()->filterByType(static::TYPE);
    }
}
