<?php namespace Dpay\Abstracts\Api\Services\Customers;
// Dpay
use Dpay\Data\Customer;

/**
 * Template class for generating Customer
 * 
 * @property string    $id        Generated Customer ID
 * @property Customer  $customer  Customer Data
 * @property string    $errorMsg
 */
interface CreateCustomerInterface extends ACrudCustomerInterface {

}