<?php namespace Dpay\Abstracts\Api\Services\Customers;
// Lib
use Dpay\Data\Customer as CustomerData;

/**
 * FetchCustomerInterface
 * Template class for fetching a Customer
 * 
 * @property string              $id       API Customer ID
 * @property CustomerData        $dpayCustomer
 * @property string              $errorMsg
 */
interface FetchCustomerInterface extends ACrudCustomerInterface {

}