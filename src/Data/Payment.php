<?php namespace Dpay\Data;
// SimpleArray
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
 * @property SimpleArray $metadata
 */
class Payment extends Data {
    const FIELDS_BOOL = ['success'];
    const FIELDS_NUMERIC = ['amount'];
    const FIELDS_STRING = [
        'id', 'transactionid', 'type', 'status', 'ordernbr', 'custid', 'status', 'method',
        'errorCode', 'errorMsg',
    ];
    const TYPES = ['charge', 'paymentlink'];
    
    public function __construct() {
        parent::__construct();

        $this->metadata = new SimpleArray();
    }

    public function getArray() : array
    {
        $data = $this->data;
        $data['metadata'] = $this->metadata->getArray();
        return $data;
    }
}