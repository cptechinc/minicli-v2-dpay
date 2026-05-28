<?php namespace Dpay\Abstracts\Api\Services\Customers;
// Dpay
use Dpay\Data\Customer;

/**
 * Template class for updating Customer
 * 
 * @property string    $id            API Customer ID
 * @property Customer  $dpayCustomer  Customer Data
 * @property string    $errorMsg
 */
interface UpdateCustomerInterface extends ACrudCustomerInterface {

}