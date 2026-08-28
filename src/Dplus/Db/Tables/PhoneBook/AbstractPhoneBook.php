<?php namespace Dpay\Dplus\Db\Tables\PhoneBook;

// Dplus Models
use PhoneBookQuery as Query;
use PhoneBook as Record;
// Pauldro Minicli
use Pauldro\Minicli\v2\Database\Propel\AbstractQueryWrapper;

/**
 * Handles Reading Records from PhoneBook Table
 *
 * @method Query   query()
 * @method Record newRecord()
 * @static self  $instance
 */
class AbstractPhoneBook extends AbstractQueryWrapper
{
    public const MODEL 			 = 'PhoneBook';
    public const MODEL_KEY 		 = '';
    public const MODEL_TABLE		 = 'phoneadr';
    public const DESCRIPTION		 = 'PhoneBook';
    public const DESCRIPTION_RECORD = 'PhoneBook';

    #[\Override]
    protected static $instance;

    /* =============================================================
        Reads
    ============================================================= */
    /**
     * Return Contact name
     * @param  string $id Sales Person ID
     */
    public function name($id): string
    {
        $q = $this->query();
        $q->filterById($id);
        $q->select(Record::aliasproperty('name'));
        return $q->findOne();
    }
}
