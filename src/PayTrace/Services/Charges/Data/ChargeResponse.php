<?php namespace Dpay\PayTrace\Services\Charges\Data;
// Pauldro
use Pauldro\UtilityBelt\Data\Data;

/**
 * @property string $success
 * @property string $transactionid
 * @property string $errorMsg
 * @property string $errorCode
 * @property string $authCode
 * @property string $avsCode
 * @property string $responseCode
 */
class ChargeResponse extends Data {
    public function __construct() {
        $this->success = false;
        $this->transactionid = '';
        $this->errorMsg  = '';
        $this->errorCode = '';
        $this->authCode  = '';
        $this->avsCode   = '';
        $this->responseCode = '';
    }
}