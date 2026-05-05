<?php namespace Dpay\Abstracts\Api\Services\Customers;
// Dpay
use Dpay\Data\Customer as CustomerData;

/**
 * Template class for fetching a Customer
 * 
 * @property string              $id       API Customer ID
 * @property CustomerData        $dpayCustomer
 * @property string              $errorMsg
 */
interface FetchCustomerInterface extends ACrudCustomerInterface {

}