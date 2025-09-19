<?php namespace Dpay\PayTrace\Api\Services\Charges;
// Dpay
use Dpay\Data\Charge as DpayCharge;
use Dpay\PayTrace\Config;


class RefundCharge extends AbstractCrudCharge {
    const ENDPOINT = 'sale/keyed';
    const ACTION_DESCRIPTION = 'refund charge';

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