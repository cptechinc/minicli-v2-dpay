<?php namespace Dpay\Abstracts\Api\Services\Refunds;
// Dpay
use Dpay\Data\Refund as DpayRefund;

trait ACrudRefundTraits {
/* =============================================================
	Inits
============================================================= */
	/**
	 * Init Dpay Refund
	 * @return bool
	 */
	protected function initDpayRefund() : bool
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
	 * @param  DpayRefund $refund
	 * @return void
	 */
	public function setDpayRefund(DpayRefund $dpayRefund) : void
	{
		$this->dpayRefund = $dpayRefund;
	}

	/**
	 * Return Dpay Credit Refund
	 * @return DpayRefund
	 */
	public function getDpayRefund() : DpayRefund
	{
		return $this->dpayRefund;
	}

	/**
	 * Set API ID
	 * @param  string $id  ID / Slug for API ID
	 * @return void
	 */
	public function setId($id) : void
	{
		$this->id = $id;
	}

	/**
	 * Return API Credit Refund ID
	 * @return string
	 */
	public function getId() : string
	{
		return $this->id;
	}
}