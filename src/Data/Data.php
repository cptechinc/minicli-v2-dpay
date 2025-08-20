<?php namespace Dpay\Data;
// Pauldro\Minicli\Data
use Pauldro\Minicli\v2\Util\Data as AbstractData;

/**
 * Data
 * Container For Dpay Data
 */
class Data extends AbstractData {
    const FIELDS_NUMERIC = [];
	const FIELDS_NUMERIC_INT = [];
	const FIELDS_NUMERIC_FLOAT = [];
	const FIELDS_STRING = [];
    const FIELDS_EASY_SET_JSON = [];

/* =============================================================
	Constructors / Inits
============================================================= */
    public function __construct() {
        foreach (static::FIELDS_NUMERIC as $fieldname) {
            $this->$fieldname = 0;
        }
        foreach (static::FIELDS_STRING as $fieldname) {
            $this->$fieldname = '';
        }
    }

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set Fields fom JSON array
	 * @param  array $data
	 * @return bool
	 */
	public function setFromJson(array $data) : void {
		foreach (static::FIELDS_EASY_SET_JSON as $fieldname) {
			if (array_key_exists($fieldname, $data) === false) {
				continue;
			}

			if (array_key_exists($fieldname, self::FIELDS_NUMERIC_FLOAT)) {
				$this->$fieldname = floatval($data[$fieldname]);
				continue;
			}

			if (array_key_exists($fieldname, self::FIELDS_NUMERIC_INT)) {
				$this->$fieldname = intval($data[$fieldname]);
				continue;
			}
			$this->$fieldname = $data[$fieldname];
		}
	}

/* =============================================================
	Getters
============================================================= */
    /**
     * Return Data as array
     * @return array
     */
    public function toArray() : array
	{
        return $this->data;
    }
}