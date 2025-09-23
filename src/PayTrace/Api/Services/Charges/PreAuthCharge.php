<?php namespace Dpay\PayTrace\Api\Services\Charges;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\PreAuthChargeInterface;
use Dpay\Data\Charge as DpayCharge;
use Dpay\PayTrace\Config;
use Dpay\Util\ChargeStatus;


class PreAuthCharge extends AbstractCrudCharge implements PreAuthChargeInterface {
    const ACTION = 'pre-authorize';
	const API_SUCCESS_RESPONSE_CODES = [101];
	const ENDPOINT = 'authorization/keyed';

/* =============================================================
	Interface Contracts
============================================================= */
	protected function getSuccessfulChargeStatus() : ChargeStatus
	{
		return ChargeStatus::RequiresCapture;
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
			'integrator_id' => Config::instance()->integratorID,
			'invoice_id'    => $charge->ordernbr,
			"amount"        => $charge->amount,
			"credit_card"=> [
				 "number"           => $charge->card->cardnbr,
				 "expiration_month" => $charge->card->expiredateMonth(),
				 "expiration_year"  => $charge->card->expiredateYear()
			],
			"csc"             => $charge->card->cvc,
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