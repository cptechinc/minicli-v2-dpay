<?php namespace Dpay\Stripe\Api\Data\PaymentLinks;
// Lib
use Dpay\Stripe\Api\Data\Data;

/**
 * Metadata
 * 
 * Container for PaymentLink metadata
 * @property string $custid   Customer ID
 * @property string $ordernbr  Sales Order Number
 */
class Metadata extends Data {
    public function __construct() {
        $this->custid = '';
        $this->ordernbr = '';
    }
}