<?php namespace Dpay\Abstracts\Api\Services\Charges;
// Dpay
use Dpay\Data\Charge as DpayCharge;

trait ACrudChargeTraits {
/* =============================================================
	Inits
============================================================= */
    protected function initDpayCharge() : bool
	  {
		if (empty($this->dpayCharge)) {
			$this->errorMsg = 'Charge Data not set';
			return false;
		}
		return true;
	}
    
/* =============================================================
	Interface Contracts
============================================================= */
    /**
	 * Set Dpay Credit Charge
	 * @param  DpayCharge $charge
	 * @return void
	 */
	public function setDpayCharge(DpayCharge $dpayCharge) : void
	{
		$this->dpayCharge = $dpayCharge;
	}

	/**
	 * Return Dpay Credit Charge
	 * @return DpayCharge
	 */
	public function getDpayCharge() : DpayCharge
	{
		return $this->dpayCharge;
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
	 * Return API Credit Charge ID
	 * @return string
	 */
	public function getId() : string
	{
		return $this->id;
	}

}