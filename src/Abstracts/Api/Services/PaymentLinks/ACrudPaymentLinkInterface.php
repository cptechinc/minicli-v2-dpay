<?php namespace Dpay\Abstracts\Api\Services\PaymentLinks;

// Dpay
use Dpay\Abstracts\Api\AbstractServiceInterface;
use Dpay\Data\PaymentLink as DpayPaymentLink;

/**
 * Template interface for PaymentLink CRUD operations
 *
 * @property string 			$id               API Payment Link ID
 * @property string 			$url	          Payment Link URL
 * @property DpayPaymentLink	$dpayPaymentLink  Dpay PaymentLink Data
 * @property string             $errorMsg
 */
interface ACrudPaymentLinkInterface extends AbstractServiceInterface
{
    /* =============================================================
            Setters
        ============================================================= */
    /**
     * Set DpayPaymentLink
     */
    public function setDpayPaymentLink(DpayPaymentLink $link): void;

    /* =============================================================
            Getters
        ============================================================= */
    /**
     * Return generated Payment Link URL
     */
    public function getUrl(): string;

    /**
     * Return Generated Payment Link ID
     */
    public function getId(): string;

    /**
     * Return if Error has occurred
     */
    public function hasError(): bool;

    /**
     * Return Response data as Dpay Payment Link
     */
    public function getDpayPaymentLinkResponseData(): DpayPaymentLink;
}
