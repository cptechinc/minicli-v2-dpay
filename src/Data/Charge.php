<?php namespace Dpay\Data;

/**
 * Charge
 * Container for Charge Data
 * 
 * @property string      $transactionid    API ID
 * @property string      $transactiontype  Transaction Type
 * @property string      $ordernbr         Order Number
 * @property string      $custid           Customer ID
 * @property string      $acustid          API Customer ID
 * @property float       $amount
 * @property string      $status
 * @property string      $action
 * @property CreditCard  $card
 * @property string      $authCode        Authorization Code
 * @property string      $avsCode         AVS Code
 * @property string      $errorCode       Error Code
 * @property string      $errorMsg        Error Msg
 */
class Charge extends Data {
	const FIELDS_NUMERIC = ['ordernbr', 'amount'];
	const FIELDS_NUMERIC_INT = ['ordernbr'];
	const FIELDS_NUMERIC_FLOAT = ['amount'];
	const FIELDS_STRING  = [
		'transactionid', 'custid', 'ordernbr', 'status', 'action',
		'avsCode', 'authCode', 'errorCode', 'errorMsg',
	];
	const FIELDS_EASY_SET_JSON = [
		'custid', 'ordernbr', 'amount', 'transactionid', 'transactiontype'
	];
	const STATUSES = [
		'requires_confirmation' => 'requires_confirmation'
	];

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->card = new CreditCard();
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
		if (array_key_exists('custid', $data) === false) {
			return;
		}

		parent::setFromJson($data);
		$this->card->setFromJson($data['card']);
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
		$json['card'] = $this->card->toArray();
		return $json;
	}
}