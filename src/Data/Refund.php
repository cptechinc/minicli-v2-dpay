<?php namespace Dpay\Data;

/**
 * Refund
 * Container for Refund Data
 * 
 * @property string  $refundid    API ID
 * @property string  $transactionid  API transaction ID to refund
 * @property string  $status
 * @property float   $amount
 * @property Charge  $charge
 */
class Refund extends Data {
	const FIELDS_NUMERIC = [];
	const FIELDS_NUMERIC_INT = [];
	const FIELDS_NUMERIC_FLOAT = ['amount'];
	const FIELDS_STRING  = ['refundid', 'transactionid', 'status'];
	const FIELDS_EASY_SET_JSON = ['refundid'];
	const STATUSES = [
		'requires_action' => 'requires_action',
		'succeeded'       => 'succeeded',
		'failed'          => 'failed',
		'canceled'        => 'canceled',
	];

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->charge = new Charge();
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
		if (array_key_exists('transactionid', $data) === false) {
			return;
		}

		parent::setFromJson($data);
		$this->charge->setFromJson($data);
		$this->amount = $this->charge->amount;
		$this->transactionid = $this->charge->transactionid;
	}

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return Array
	 * @return array
	 */
	public function toArray() : array
	{
		$json = $this->data;
		$json['charge'] = $this->charge->toArray();
		return $json;
	}
}