<?php namespace Dpay\Abstracts\Api\Services\CreditCards;

// Dpay
use Dpay\Abstracts\Api\AbstractServiceInterface;
use Dpay\Data\CreditCard as CreditCard;

/**
 * Template class for CreditCard CRUD operations
 *
 * @property string      $id              API CreditCard ID
 * @property CreditCard  $dpayCreditCard  CreditCard Data
 * @property string      $errorMsg
 */
interface ACrudCreditCardInterface extends AbstractServiceInterface
{
    /* =============================================================
            Setters
        ============================================================= */
    /**
     * Set CreditCard
     */
    public function setDpayCreditCard(CreditCard $card): void;

    /* =============================================================
            Getters
        ============================================================= */
    /**
     * Return Generated CreditCard ID
     */
    public function getId(): string;

    /**
     * Return if Error has occurred
     */
    public function hasError(): bool;

    /**
     * Return Response data as Dpay Credit Card
     */
    public function getDpayCreditCardResponseData(): CreditCard;
}
