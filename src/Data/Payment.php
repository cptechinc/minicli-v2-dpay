<?php namespace Dpay\Data;

// Pauldro Minicli
use Pauldro\Minicli\v2\Util\SimpleArray;

/**
 * @property string $id
 * @property string $transactionid
 * @property string $type
 * @property float  $amount
 * @property string $ordernbr
 * @property string $custid
 * @property string $status
 * @property string $method
 * @property bool   $success
 * @property string $errorCode
 * @property string $errorMsg
 * @property string $authCode
 * @property SimpleArray $metadata
 */
class Payment extends Data
{
    public const FIELDS_BOOL = ['success'];
    public const FIELDS_NUMERIC = ['amount'];
    public const FIELDS_STRING = [
        'id', 'transactionid', 'type', 'status', 'ordernbr', 'custid', 'status', 'method',
        'errorCode', 'errorMsg', 'authCode'
    ];
    public const TYPES = ['charge', 'paymentlink'];

    public function __construct()
    {
        parent::__construct();

        $this->metadata = new SimpleArray();
    }

    public function getArray(): array
    {
        $data = $this->data;
        $data['metadata'] = $this->metadata->getArray();
        return $data;
    }
}
