<?php namespace Dpay\Dplus\Db\Tables\PhoneBook;
// Dplus Models
use PhoneBookQuery as Query;
use PhoneBook as Record;

class Customer extends AbstractPhoneBookType {
	const TYPE = 'C';

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query filtered for cust ID
	 * @param string $custID
	 * @return Query
	 */
	public function queryCustid($custID) : Query
	{
		return $this->queryType()->filterByKey1($custID);
	}

/* =============================================================
	Read Functions
============================================================= */	
	/**
	 * Return the number of contacts for Customer
	 * @param  string $custID
	 * @return int
	 */
	public function countByCustid($custID) : int
	{
		return $this->queryCustid($custID)->count();
	}

	/**
	 * Return the first record that matches
	 * @param  string $custID
	 * @param  string $contactID
	 * @return Record
	 */
	public function findOne($custID, $contactID = '') : Record
	{
		$q = $this->queryCustid($custID);
		if ($contactID) {
			$q->filterByContactid($contactID);
		}
		return $q->findOne();
	}
}