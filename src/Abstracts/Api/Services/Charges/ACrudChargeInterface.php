<?php namespace Dpay\Abstracts\Api\Services\Charges;

// Dpay
use Dpay\Abstracts\Api\AbstractServiceInterface;
use Dpay\Data\Charge as Charge;

/**
 * Template class for Charge CRUD operations
 *
 * @property string  $id          API Charge ID
 * @property Charge  $dpayCharge  Charge Data
 * @property string  $errorMsg
 */
interface ACrudChargeInterface extends AbstractServiceInterface
{
    /* =============================================================
            Setters
        ============================================================= */
    /**
     * Set Charge
     */
    public function setDpayCharge(Charge $card): void;

    /* =============================================================
            Getters
        ============================================================= */
    /**
     * Return Generated Charge ID
     */
    public function getId(): string;

    /**
     * Return if Error has occurred
     */
    public function hasError(): bool;

    /**
     * Return Response data as Dpay Credit Card
     */
    public function getDpayChargeResponseData(): Charge;
}
