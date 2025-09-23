<?php namespace Dpay\AuthorizeNet\Api\Services\Charges\Data;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\Data;

/**
 * @property string $success
 * @property string $transactionid
 * @property string $errorMsg
 * @property string $errorCode
 * @property string $authCode
 * @property string $avsCode
 * @property string $status
 */
class ChargeResponse extends Data {
    public function __construct() {
        $this->success = false;
        $this->transactionid = '';
        $this->errorMsg  = '';
        $this->errorCode = '';
        $this->authCode  = '';
        $this->avsCode   = '';
        $this->status    = '';
    }
}