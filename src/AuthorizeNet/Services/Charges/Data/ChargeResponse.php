<?php namespace Dpay\AuthorizeNet\Services\Charges\Data;
// Pauldro Minicli
use Pauldro\UtilityBelt\Data\Data;

/**
 * @property bool   $success
 * @property string $transactionid  API Transaction ID
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