<?php namespace Dpay\Abstracts\Api\Services\Refunds;

// Dpay
use Dpay\Data\Refund as Refund;

/**
 * Template class for Refund CRUD operations
 *
 * @property string  $id          API Refund ID (Generated)
 * @property Refund  $dpayRefund  Refund Data
 * @property string  $errorMsg
 */
interface CreateRefundInterface extends ACrudRefundInterface
{
    /* =============================================================
        Setters
    ============================================================= */

    /* =============================================================
        Getters
    ============================================================= */
}
