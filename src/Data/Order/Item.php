<?php namespace Dpay\Data\Order;
// Lib
use Dpay\Data\Data;

/**
 * Item
 * Container for Order Item Data
 * 
 * @property string $aid           API ID
 * @property string $linetype      Line Type (invoice or item)
 * @property string $ordernbr      Order #
 * @property int    $linenbr       Line Number
 * @property string $itemid        Item ID
 * @property string $description   Item Description
 * @property float  $qty           Qty Ordered
 * @property float  $price         Item Price (Each)
 * @property string $vendoritemid  Vendor's Item ID
 * @property string $isNonstock    Is Item Nonstock?
 */
class Item extends Data {
	const FIELDS_NUMERIC = ['linenbr', 'qty', 'price'];
	const FIELDS_NUMERIC_INT = ['linenbr', 'ordernbr'];
	const FIELDS_NUMERIC_FLOAT = ['qty', 'price'];
	const FIELDS_STRING  = ['aid', 'itemid', 'description', 'linetype', 'vendoritemid'];
	const FIELDS_EASY_SET_JSON = ['linenbr', 'qty', 'price', 'itemid', 'description', 'linetype', 'ordernbr', 'vendoritemid'];
	const ITEMID_NONSTOCK = 'N';
	const LINETYPES = ['item', 'invoice', 'batch'];

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->linetype = 'item';
		$this->isNonstock = false;
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
		parent::setFromJson($data);

		if ($this->itemid == self::ITEMID_NONSTOCK) {
			$this->isNonstock = true;
		}
	}

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return Price as Cents
	 * @return int
	 */
	public function priceInCents() : int
	{
		return $this->price * 100;
	}

	/**
	 * Return if Line Item is batch (merged invoices)
	 * @return bool
	 */
	public function isLinetypeBatch() : bool
	{
		return $this->linetype == 'batch';
	}

	/**
	 * Return if Line Item is an Invoice
	 * @return bool
	 */
	public function isLinetypeInvoice() : bool
	{
		return $this->linetype == 'invoice';
	}

	/**
	 * Return if Line Item is an Item (Product)
	 * @return bool
	 */
	public function isLinetypeItem() : bool
	{
		return $this->linetype == 'item';
	}

	/**
	 * Return itemid based on Line Type
	 * @return string
	 */
	public function itemid() : string
	{
		if ($this->isLinetypeInvoice()) {
			return "invc_$this->ordernbr";
		}
		if ($this->isLinetypeBatch()) {
			return "batch_$this->ordernbr";
		}
		if ($this->isNonstock) {
			return "$this->itemid$this->vendoritemid";
		}
		return $this->itemid;
	}
}