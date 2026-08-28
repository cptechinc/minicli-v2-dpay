<?php namespace Dpay\Data;

/**
 * Container for Refund Data
 *
 * @property string  $refundid       API ID
 * @property string  $transactionid  API transaction ID to refund
 * @property string  $status
 * @property float   $amount
 * @property Charge  $charge
 */
class Refund extends Data
{
    public const FIELDS_NUMERIC = [];
    public const FIELDS_NUMERIC_INT = [];
    public const FIELDS_NUMERIC_FLOAT = ['amount'];
    public const FIELDS_STRING  = ['refundid', 'transactionid', 'status'];
    public const FIELDS_EASY_SET_JSON = ['refundid'];
    public const STATUSES = [
        'requires_action' => 'requires_action',
        'succeeded'       => 'succeeded',
        'failed'          => 'failed',
        'canceled'        => 'canceled',
    ];

    /* =============================================================
        Constructors / Inits
    ============================================================= */
    public function __construct()
    {
        parent::__construct();
        $this->charge = new Charge();
    }

    /* =============================================================
        Setters
    ============================================================= */
    /**
     * Set Fields fom JSON array
     */
    public function setFromJson(array $data): void
    {
        if (array_key_exists('transactionid', $data) === false) {
            return;
        }

        parent::setFromJson($data);
        $this->charge->setFromJson($data);
        $this->amount = $this->charge->amount;
        $this->transactionid = $this->charge->transactionid;
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
        $json['charge'] = $this->charge->getArray();
        return $json;
    }
}
