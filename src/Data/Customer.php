<?php namespace Dpay\Data;

/**
 * Container for Customer Data
 *
 * @property string  $aid             API ID
 * @property string  $custid          Customer ID
 * @property string  $custname        Customer Name
 * @property string  $billtoaddress1  Bill-To Address 1
 * @property string  $billtoaddress2  Bill-To Address 2
 * @property string  $billtoaddress3  Bill-To Address 3
 * @property string  $billtocity      Bill-To City
 * @property string  $billtostate     Bill-To State
 * @property string  $billtozipcode   Bill-To Zip Code
 * @property string  $billtocountry   Bill-To Country
 * @property string  $email           E-mail Address
 * @property string  $phonenbr        Phone Number
 */
class Customer extends Data
{
    public const FIELDS_NUMERIC = [];
    public const FIELDS_NUMERIC_INT = [];
    public const FIELDS_NUMERIC_FLOAT = [];
    public const FIELDS_STRING  = [
        'custid', 'custname',
        'billtoaddress1', 'billtoaddress2', 'billtoaddress3',
        'billtocity', 'billtostate', 'billtozipcode', 'billtocountry',
        'email', 'phonenbr'
    ];
    public const FIELDS_EASY_SET_JSON = [
        'custid', 'custname',
        'billtoaddress1', 'billtoaddress2', 'billtoaddress3',
        'billtocity', 'billtostate', 'billtozipcode', 'billtocountry',
    ];

    /* =============================================================
        Constructors / Inits
    ============================================================= */
    /* =============================================================
        Setters
    ============================================================= */
    /**
     * Set Fields fom JSON array
     */
    public function setFromJson(array $data): void
    {
        if (array_key_exists('custid', $data) === false) {
            return;
        }
        parent::setFromJson($data);
    }
}
