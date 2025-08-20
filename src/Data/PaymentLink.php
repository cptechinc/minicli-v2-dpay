<?php namespace Dpay\Data;

/**
 * PaymentLink
 * Container for PaymentLink Data
 * 
 * @property string $id        Payment Link ID / URL
 * @property string $url       Payment Link URL
 * @property bool   $isActive  Return if Payment Link is Active
 * @property Order  $order
 */
class PaymentLink extends Data {
	const FIELDS_STRING  = ['id', 'url'];

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->isActive = false;
		$this->order    = new Order();
	}

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set Fields fom JSON array
	 * @param  array $data
	 * @return void
	 */
	public function setFromJson(array $data) : void {
		if (array_key_exists('ordernbr', $data) === false) {
			return;
		}
		$this->order->setFromJson($data);
	}
}