<?php namespace Dpay\AuthorizeNet\Api\Services\Refunds\Data;
// Dpay
use Dpay\AuthorizeNet\Api\Services\Charges\Data\ChargeResponse;

/**
 * @property string $success
 * @property string $transactionid
 * @property string $errorMsg
 * @property string $errorCode
 * @property string $authCode
 * @property string $status
 */
class RefundResponse extends ChargeResponse {
    
}