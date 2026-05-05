<?php namespace Dpay\Abstracts\Api\Services\Customers;
// Dpay
use Dpay\Data\Customer;

/**
 * Template class for updating Customer
 * 
 * @property Customer  $dpayCustomer  Customer Data
 * @property string    $id            Updated Customer ID
 * @property string    $errorMsg
 */
interface UpdateCustomerInterface extends ACrudCustomerInterface {

}