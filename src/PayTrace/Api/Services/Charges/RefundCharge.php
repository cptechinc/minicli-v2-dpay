<?php namespace Dpay\PayTrace\Api\Services\Charges;
// Dpay
use Dpay\Data\Charge as DpayCharge;
use Dpay\PayTrace\Api\Services\Charges\Data\ChargeResponse;
use Dpay\PayTrace\Config;
use Dpay\Util\ChargeStatus;


class RefundCharge extends AbstractCrudCharge {
    const ACTION = 'refund';
	const API_SUCCESS_RESPONSE_CODES = [106];
	const ENDPOINT = 'sale/keyed';
	
/* =============================================================
	Interface Contracts
============================================================= */
	protected function getSuccessfulChargeStatus(ChargeResponse $response) : ChargeStatus
	{
		if (array_key_exists($response->responseCode, self::API_SUCCESS_RESPONSE_CODES) === false){
			return ChargeStatus::None;
		}
		return ChargeStatus::Refunded;
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
			'invoice_id'      => $charge->ordernbr,
			"transaction_id"  => $charge->transactionid,
			"amount"          => $charge->amount,
			"billing_address" => [
				"name"           => $charge->card->name,
				"street_address" => $charge->card->address1,
				"city"           => "",
				"state"          => "",
				"zip"            => $charge->card->zipcode
			]
		];
	}
}