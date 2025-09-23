<?php namespace Dpay\Abstracts\Api\Services\PaymentLinks;
// Dpay
use Dpay\Data\PaymentLink as DpayPaymentLink;

trait ACrudPaymentLinkTraits {
/* =============================================================
	Inits
============================================================= */
	/**
	 * Init Dpay PaymentLink
	 * @return bool
	 */
	protected function initDpayPaymentLink() : bool
    {
		if (empty($this->dpayPaymentLink)) {
			$this->errorMsg = 'PaymentLink Data not set';
			return false;
		}
		return true;
	}

/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Set Dpay PaymentLink
	 * @param  DpayPaymentLink $link
	 * @return void
	 */
	public function setDpayPaymentLink(DpayPaymentLink $dpayPaymentLink) : void
	{
		$this->dpayPaymentLink = $dpayPaymentLink;
	}

	/**
	 * Return Dpay PaymentLink
	 * @return DpayPaymentLink
	 */
	public function getDpayPaymentLink() : DpayPaymentLink
    {
		return $this->dpayPaymentLink;
	}

	/**
	 * Set API ID
	 * @param  string $id
	 * @return void
	 */
	public function setId($id) : void
    {
		$this->id = $id;
	}

	/**
	 * Return API PaymentLink ID
	 * @return string
	 */
	public function getId() : string
    {
		return $this->id;
	}

	/**
	 * Return Payment Link URL
	 * @return string
	 */
	public function getUrl() : string
    {
		return $this->url;
	}
}