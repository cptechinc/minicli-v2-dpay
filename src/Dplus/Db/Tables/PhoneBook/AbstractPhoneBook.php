<?php namespace Dpay\Dplus\Db\Tables\PhoneBook;
// Dplus Models
use PhoneBookQuery as Query, PhoneBook as Record;
// Pauldro Minicli
use Pauldro\Minicli\v2\Database\Propel\AbstractQueryWrapper;

/**
 * Handles Reading Records from PhoneBook Table
 * 
 * @method Query   query()
 * @method Record newRecord()
 * @static self  $instance
 */
class AbstractPhoneBook extends AbstractQueryWrapper {
	const MODEL 			 = 'PhoneBook';
	const MODEL_KEY 		 = '';
	const MODEL_TABLE		 = 'phoneadr';
	const DESCRIPTION		 = 'PhoneBook';
	const DESCRIPTION_RECORD = 'PhoneBook';

	protected static $instance;

/* =============================================================
	Reads
============================================================= */
	/**
	 * Return Contact name
	 * @param  string $id Sales Person ID
	 * @return string
	 */
	public function name($id) : string
	{
		$q = $this->query();
		$q->filterById($id);
		$q->select(Record::aliasproperty('name'));
		return $q->findOne();
	}
}