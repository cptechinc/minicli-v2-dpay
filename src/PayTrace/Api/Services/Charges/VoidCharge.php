<?php namespace Dpay\PayTrace\Api\Services\Charges;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\VoidChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\PayTrace\Api\Services\Charges\Data\ChargeResponse;
use Dpay\PayTrace\Config;
use Dpay\Util\ChargeStatus;

class VoidCharge extends AbstractCrudCharge implements VoidChargeInterface {
    const ACTION = 'void';
	const API_SUCCESS_RESPONSE_CODES = [106];
	const ENDPOINT = 'void';

/* =============================================================
	Interface Contracts
============================================================= */
	protected function getSuccessfulChargeStatus(ChargeResponse $response) : ChargeStatus
	{
		if (array_key_exists($response->responseCode, self::API_SUCCESS_RESPONSE_CODES) === false){
			return ChargeStatus::None;
		}
		return ChargeStatus::Voided;
	}
	
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