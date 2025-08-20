<?php namespace Dpay\Abstracts\Api\Services\Customers;
// Lib
use Dpay\Data\Customer;

/**
 * UpdateCustomerInterface
 * Template class for updating Customer
 * 
 * @property Customer  $dpayCustomer  Customer Data
 * @property string    $id            Updated Customer ID
 * @property string    $errorMsg
 */
interface UpdateCustomerInterface extends ACrudCustomerInterface {

}