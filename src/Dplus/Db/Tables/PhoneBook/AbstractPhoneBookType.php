<?php namespace Dpay\Dplus\Db\Tables\PhoneBook;
// Dplus Models
use PhoneBookQuery as Query;

/**
 * AbstractType
 * Template Class for querying phoneadr records from database
 */
class AbstractPhoneBookType extends AbstractPhoneBook {
	const TYPE = '';

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query Filtered by type
	 * @return Query
	 */
	public function queryType() : Query
	{
		return $this->query()->filterByType(static::TYPE);
	}
}