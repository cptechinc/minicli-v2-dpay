<?php namespace Dpay\Logs\Database\Data;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\SimpleArray;
// Dpay
use Dpay\Abstracts\Database\MeekroDB\AbstractRecord;


/**
 * Container for PaymentLink Status Record
 * 
 * @property int|null $rid		  Record ID
 * @property string   $created	  Timestamp
 * @property string   $updated	  Timestamp
 * @property int	  $conbr	  Company Number
 * @property string   $linkid	  Link ID
 * @property string   $url		  Link URL
 * @property string   $transactionid
 * @property float	  $amount	  Total Amount
 * @property string   $paymentstatus
 * @property bool	  $isActive
 * @property bool	  $isComplete
 * @property string   $errorCode
 * @property string   $errorMsg
 * @property string   $authCode
 * @property string   $raw_metadata
 * @property SimpleArray $metadata
 */
class PaymentLinkStatusRecord extends AbstractRecord {
    public $metadata;

    const DEFAULT_VALUES = [
        'created'	  => '',
        'updated'	  => '',
        'conbr' 	  => 0,
        'linkid'	  => '',
        'url'		  => '',
        'transactionid' => '',
        'amount'		=> 0.00,
        'paymentstatus' => 'unpaid',
        'isActive'		=> true,
        'isComplete'	=> false,
        'errorCode' 	=> '',
        'errorMsg'   	=> '',
        'authCode'		=> '',
        'raw_metadata'	=> ''
    ];

    public function __construct() {
        foreach (self::DEFAULT_VALUES as $field => $value) {
            if ($this->has($field)) {
                continue;
            }
            $this->set($field, $value);
        }
        $this->metadata = new SimpleArray();
    }

    public function setArray(array $data) : static
    {
        parent::setArray($data);

        if ($this->raw_metadata != '') {
            $this->metadata->setArray(json_decode($this->raw_metadata));
        }
        $this->isActive   = boolval($this->isActive);
        $this->isComplete = boolval($this->isComplete);
        return $this;
    }
}