<?php namespace Dpay\Data;

/**
 * Order
 * 
 * Container for Order Data
 * 
 * @property int     $ordernbr           Sales Order Number
 * @property string  $type               Order Type
 * @property string  $custid             Customer ID
 * @property Order\ItemsList<\Dpay\Data\Order\Item> $items  List of Sales Order Items
 */
class Order extends Data {
	const TYPES = ['invoice', 'invoices', 'consolidated'];
	const FIELDS_NUMERIC = ['ordernbr'];
	const FIELDS_NUMERIC_INT = ['ordernbr'];
	const FIELDS_NUMERIC_FLOAT = [];
	const FIELDS_STRING  = ['type', 'custid'];
	const FIELDS_EASY_SET_JSON = ['ordernbr', 'custid', 'type'];

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->items = new Order\ItemsList();
		$this->type  = 'invoice';
	}

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set Fields fom JSON array
	 * @param  array $data
	 * @return void
	 */
	public function setFromJson(array $data) : void
	{
		if (array_key_exists('ordernbr', $data) === false) {
			return;
		}

		parent::setFromJson($data);

		if (array_key_exists('items', $data) === false) {
			return;
		}
		if ($this->items->importFromJson($data['items']) === false) {
			return;
		}
	}
	
/* =============================================================
	Getters
============================================================= */
	public function getArray() : array
	{
		$data = $this->data;
		$data['items'] = $this->items->getArray();
		return $data;
	}
}