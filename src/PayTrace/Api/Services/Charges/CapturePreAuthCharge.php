<?php namespace Dpay\PayTrace\Api\Services\Charges;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\CapturePreAuthChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\PayTrace\Config;


class CapturePreAuthCharge extends AbstractCrudCharge implements CapturePreAuthChargeInterface {
    const ENDPOINT = 'authorization/capture';
	const ACTION_DESCRIPTION = 'capture pre-authorized charge';

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
			'integrator_id'  => Config::instance()->integratorID,
			'transaction_id' => $charge->transactionid
		];
	}
}