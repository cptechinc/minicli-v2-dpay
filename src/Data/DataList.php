<?php namespace Dpay\Data;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\DataArray;

/**
 * DataList
 * 
 * Container for List of Data
 */
class DataList extends DataArray {
/* =============================================================
	Setters
============================================================= */
	/**
	 * Add Items from JSON data
	 * @param  array $data
	 * @return bool
	 */
	public function importFromJson(array $data) : bool
	{
		foreach ($data as $itemData) {
			$item = $this->new();
			$item->setFromJson($itemData);
			$this->add($item);
		}
		return $this->count() > 0;
	}

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return new Item
	 * @return Data
	 */
	public function new() : Data
	{
		return new Data();
	}
}