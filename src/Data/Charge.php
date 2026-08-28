<?php namespace Dpay\Data;

/**
 * Container for Charge Data
 *
 * @property string      $transactionid    API ID
 * @property string      $transactiontype  Transaction Type
 * @property string      $ordernbr         Order Number
 * @property string      $custid           Customer ID
 * @property string      $acustid          API Customer ID
 * @property float       $amount
 * @property string      $status
 * @property string      $action
 * @property CreditCard  $card
 * @property string      $authCode        Authorization Code
 * @property string      $avsCode         AVS Code
 * @property string      $errorCode       Error Code
 * @property string      $errorMsg        Error Msg
 */
class Charge extends Data
{
    public const FIELDS_NUMERIC = ['ordernbr', 'amount'];
    public const FIELDS_NUMERIC_INT = ['ordernbr'];
    public const FIELDS_NUMERIC_FLOAT = ['amount'];
    public const FIELDS_STRING  = [
        'transactionid', 'custid', 'ordernbr', 'status', 'action',
        'avsCode', 'authCode', 'errorCode', 'errorMsg',
    ];
    public const FIELDS_EASY_SET_JSON = [
        'custid', 'ordernbr', 'amount', 'transactionid', 'transactiontype'
    ];
    public const STATUSES = [
        'requires_confirmation' => 'requires_confirmation'
    ];

    /* =============================================================
        Constructors / Inits
    ============================================================= */
    public function __construct()
    {
        parent::__construct();
        $this->card = new CreditCard();
    }

    /* =============================================================
        Setters
    ============================================================= */
    /**
     * Set Fields fom JSON array
     */
    public function setFromJson(array $data): void
    {
        if (array_key_exists('custid', $data) === false) {
            return;
        }

        parent::setFromJson($data);
        $this->card->setFromJson($data['card']);
    }

    /* =============================================================
        Getters
    ============================================================= */
    /**
     * Return Array
     */
    public function getArray(): array
    {
        $json = $this->data;
        $json['card'] = $this->card->getArray();
        return $json;
    }
}
