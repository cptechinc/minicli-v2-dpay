<?php namespace Dpay\Abstracts\Api\Services\Refunds;

// Dpay
use Dpay\Abstracts\Api\AbstractServiceInterface;
use Dpay\Data\Refund as Refund;

/**
 * Template class for Refund CRUD operations
 *
 * @property string  $id          API Refund ID
 * @property Refund  $dpayRefund  Refund Data
 * @property string  $errorMsg
 */
interface ACrudRefundInterface extends AbstractServiceInterface
{
    /* =============================================================
            Setters
        ============================================================= */
    /**
     * Set Refund
     */
    public function setDpayRefund(Refund $card): void;

    /* =============================================================
            Getters
        ============================================================= */
    /**
     * Return Generated Refund ID
     */
    public function getId(): string;

    /**
     * Return if Error has occurred
     */
    public function hasError(): bool;

    /**
     * Return Response data as Dpay Refund
     */
    public function getDpayRefundResponseData(): Refund;
}
