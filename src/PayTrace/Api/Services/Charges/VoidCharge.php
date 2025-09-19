<?php namespace Dpay\PayTrace\Api\Services\Charges;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\VoidChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\PayTrace\Config;


class VoidCharge extends AbstractCrudCharge implements VoidChargeInterface {
    const ENDPOINT = 'void';
    const ACTION_DESCRIPTION = 'void charge';

/* =============================================================
	Contracts
============================================================= */
	/**
	 * Generate Credit Charge Request Data
	 * @param  DpayCharge $charge
	 * @return array
	 */
	protected function generateChargeRequest(DpayCharge $charge) : array
	{
		return [
			'integrator_id'   => Config::instance()->integratorID,
			"transaction_id"  => $charge->transactionid,
		];
	}
}