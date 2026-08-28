<?php namespace Dpay\AuthorizeNet\Services\Transactions\Data;

// Pauldro Minicli
use Pauldro\UtilityBelt\Data\Data;

/**
 * @property bool   $exists
 * @property string $id
 * @property string $type
 * @property string $status
 * @property string $errorMsg
 * @property string $errorCode
 */
class Transaction extends Data
{
    public function __construct()
    {
        $this->id        = '';
        $this->type      = '';
        $this->status    = '';
        $this->success   = false;
        $this->exists    = false;
        $this->errorCode = '';
    }
}
