<?php namespace Dpay\Abstracts\Api\Services\CreditCards;

// Dpay
use Dpay\Data\CreditCard as DpayCreditCard;

trait ACrudCreditCardTraits
{
    /* =============================================================
            Interface Contracts
        ============================================================= */
    /**
     * Set Dpay Credit Card
     */
    public function setDpayCreditCard(DpayCreditCard $card): void
    {
        $this->dpayCreditCard = $card;
    }

    /**
     * Return Dpay Credit Card
     */
    public function getDpayCreditCard(): DpayCreditCard
    {
        return $this->dpayCreditCard;
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
     * Return API Credit Card ID
     */
    public function getId(): string
    {
        return $this->id;
    }
}
