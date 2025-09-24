<?php namespace Dpay\Stripe\Data\CreditCards;
// Lib
use Dpay\Stripe\Data\Data;


/**
 * CreditCardRequest
 * 
 * Data Container for Creating CreditCard
 * 
 * @property string  $id             API ID
 * @property string  $custid         Stripe Customer ID
 * @property string  $name           Card Holder Name
 * @property string  $number
 * @property string  $exp_month      Expire Month "01"
 * @property string  $exp_year       Expire Year "2023"
 * @property string  $cvc            Card Validation Code
 * @property string  $address_line1    Address Line 1
 * @property string  $address_line2    Address Line 2
 * @property string  $address_city     Address City
 * @property string  $address_state    Address State
 * @property string  $address_zip      Address Zipcode
 * @property string  $address_country  Address country
 */
class CreditCardRequest extends Data {
    const FIELDS_STRING  = [
        'id', 'custid',
        'name',
        'address_city', 'address_country',
        'address_line1', 'address_line2',
        'address_state', 'address_zip',
        'exp_month', 'exp_year',
        'number'
    ];

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		foreach (self::FIELDS_STRING as $fieldname) {
			$this->$fieldname = '';
		}
	}

    /**
     * Return Stripe Data Array
     * @return array
     */
    public function toApiArray() : array
    {
        $data = $this->toArray();
        unset($data['id']);
        unset($data['custid']);
        unset($data['number']);
		unset($data['cvc']);
        return $data;
    }
}