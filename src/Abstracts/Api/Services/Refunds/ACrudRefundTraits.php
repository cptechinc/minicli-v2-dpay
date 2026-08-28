<?php namespace Dpay\Abstracts\Api\Services\Refunds;

// Dpay
use Dpay\Data\Refund as DpayRefund;

trait ACrudRefundTraits
{
    /* =============================================================
            Inits
        ============================================================= */
    /**
     * Init Dpay Refund
     */
    protected function initDpayRefund(): bool
    {
        if (empty($this->dpayRefund)) {
            $this->errorMsg = 'Refund Data not set';
            return false;
        }
        return true;
    }

    /* =============================================================
            Interface Contracts
        ============================================================= */
    /**
     * Set Dpay Credit Refund
     */
    public function setDpayRefund(DpayRefund $refund): void
    {
        $this->dpayRefund = $refund;
    }

    /**
     * Return Dpay Credit Refund
     */
    public function getDpayRefund(): DpayRefund
    {
        return $this->dpayRefund;
    }

    /**
     * Set API ID
     * @param  string $id  ID / Slug for API ID
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Return API Credit Refund ID
     */
    public function getId(): string
    {
        return $this->id;
    }
}
