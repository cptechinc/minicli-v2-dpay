<?php namespace Dpay\Data\Order;
// Lib
use Dpay\Data\DataList;

/**
 * ItemList
 * Container for List of Items
 */
class ItemsList extends DataList {
/* =============================================================
	Setters
============================================================= */
	/**
	 * Add Items from JSON data
	 * @param  array $data
	 * @return bool
	 */
	public function importFromJson(array $data) : bool {
		foreach ($data as $itemData) {
			if (array_key_exists('linenbr', $itemData) === false) {
				continue;
			}
			$item = $this->new();
			$item->setFromJson($itemData);
			$this->set($item->linenbr, $item);
		}
		return $this->count() > 0;
	}
	
/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return new Item
	 * @return Item
	 */
	public function new() : Item
	{
		return new Item();
	}
}