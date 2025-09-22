<?php namespace Dpay\Data;

/**
 * Credit Card
 * 
 * Container for Customer Credit Card Data
 * 
 * @property string  $aid		API ID
 * @property string  $acustid	API Customer ID
 * @property string  $custid	Customer ID
 * @property string  $name		Cardholder Name
 * @property string  $address1	Address 1
 * @property string  $address2	Address 2
 * @property string  $city		City
 * @property string  $state 	State
 * @property string  $zipcode	Zip Code
 * @property string  $country	Country
 * @property string  $brand 	Credit Card Brand
 * @property string  $cardnbr	Credit Card Number
 * @property string  $track1    Track 1
 * @property string  $track2    Track 2
 * @property string  $expiredate Credit Card Expire Date
 * @property string  $cvc		 Card Verification Code
 * @property string  $last4 	 Card Last 4
 */
class CreditCard extends Data {
	const FIELDS_NUMERIC = [];
	const FIELDS_NUMERIC_INT = [];
	const FIELDS_NUMERIC_FLOAT = [];
	const FIELDS_STRING = [
		'aid', 'acustid',
		'custid', 'name', 
		'address1', 'address2', 'address3',
		'city', 'state', 'zipcode', 'country',
		'cardnbr', 'expiredate', 'cvc', 'last4', 'brand',
		'track1', 'track2'
	];
	const FIELDS_EASY_SET_JSON = [
		'custid', 'name',
		'address1', 'address2', 'address3',
		'city', 'state', 'zipcode', 'country',
		'cardnbr', 'expiredate', 'cvc'
	];

/* =============================================================
	Constructors / Inits
============================================================= */

/* =============================================================
	Getters
============================================================= */
	public function hasTrack1() : bool
	{
		return empty($this->track1) === false;
	}

	public function hasTrack2() : bool
	{
		return empty($this->track2) === false;
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
		if (array_key_exists('custid', $data) === false) {
			return;
		}
		parent::setFromJson($data);
	}

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return Expire Date Month
	 * @return string
	 */
	public function expiredateMonth() : string
	{
		$dates = explode('/', $this->expiredate);
		return $dates[0];
	}

	/**
	 * Return Expire Date Year
	 * @return string
	 */
	public function expiredateYear() : string
	{
		$dates = explode('/', $this->expiredate);
		
		if (strlen($dates[1]) == 4) {
			return $dates[1];
		}
		return '20' . $dates[1];
	}

	/**
	 * Return the last 4 digits of credit card number
	 * @return string
	 */
	public function last4() : string
	{
		if ($this->last4) {
			return $this->last4;
		}
		return substr($this->cardnbr, -4);
	}
}