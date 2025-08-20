<?php namespace Dpay\Dplus\Db\Tables;
// Dplus Models
use CustomerQuery as Query, Customer as Record;
// Dplus
use Pauldro\Minicli\v2\Database\Propel\AbstractQueryWrapper;

/**
 * Customer
 * Reads Records from Customer table
 * 
 * @method Query query()
 * @static self  $instance
 */
class Customers extends AbstractQueryWrapper {
	const MODEL              = 'Customer';
	const MODEL_KEY          = 'custid';
	const MODEL_TABLE        = 'ar_cust_mast';
	const DESCRIPTION        = 'Dplus Customers table';

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query Filtered By Customer ID
	 * @param  int   $custid
	 * @return Query
	 */
	public function queryCustid($custid) {
		return $this->query()->filterByCustid($custid);
	}
	
/* =============================================================
	Reads
============================================================= */
	/**
	 * Return if Order Exists
	 * @param  int $custid
	 * @return bool
	 */
	public function exists($custid) {
		return boolval($this->queryCustid($custid)->count());
	}

	/**
	 * Return Customer
	 * @param  string $custid
	 * @return Record
	 */
	public function findOne($custid) {
		return $this->queryCustid($custid)->findOne();
	}
}