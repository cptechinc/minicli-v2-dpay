<?php namespace Dpay\Stripe\Data\PaymentLinks;
// Lib
use Dpay\Stripe\Data\Data;

/**
 * Metadata
 * 
 * Container for PaymentLink metadata
 * @property string $custid   Customer ID
 * @property string $ordernbr  Sales Order Number
 */
class Metadata extends Data {
    public function __construct() {
        
    }

    public function count() : int
    {
        return count($this->data);
    }
}