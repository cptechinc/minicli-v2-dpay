<?php namespace Dpay\PayTrace;
// Dplus Payments Model
use Payment;

/**
 * TransactionData
 * 
 * Returns arrays parsed from Payment Request Data
 */
class TransactionData {
	public static function default(Payment $payRequest) : array
	{
		return [
			'integrator_id' => Config::instance()->integratorID,
			'invoice_id'    => $payRequest->getOrdernbr(),
		];
	}

	/**
	 * Return Data needed for Transaction ID based Requests
	 * @return array
	 */
	public static function defaultWithTransactionid(Payment $payRequest) : array
	{
		$data = [
			"transaction_id" => $payRequest->getTransId()
		];
		return array_merge(self::default($payRequest), $data);
	}

	/**
	 * Return Billing Address Data
	 * @return array
	 */
	public static function dataBillingAddress(Payment $payRequest) : array
	{
		return [
			"name"           => $payRequest->getCardName(),
			"street_address" => $payRequest->getStreet(),
			"city"           => "",
			"state"          => "",
			"zip"            => $payRequest->getZipcode()
		];
	}

	/**
	 * Return Credit Card Request Data for CARD NOT PRESENT
	 * @param Payment $request
	 * @return array
	 */
	public static function dataCreditCardNotPresent(Payment $payRequest) {
		$date = self::convertCardExpireDate($payRequest->expiredate());
		$data = [
			"amount" => $payRequest->getAmount(),
			"credit_card"=> [
				 "number"           => $payRequest->cardnumber(),
				 "expiration_month" => $date[0],
				 "expiration_year"  => $date[1]
			],
			"csc"             => $payRequest->cvv(),
			"billing_address" => self::dataBillingAddress($payRequest)
		];
		return array_merge(self::default($payRequest), $data);
	}


	/**
	 * Return Date parsed into array [month, year]
	 * @param  string $date
	 * @return array
	 */
	private static function convertCardExpireDate($date) {
		if (strpos($date, '/') !== FALSE) {
			$datearray = explode('/', $date);
			$month = $datearray[0];
			$year  = $datearray[1];

			if (strlen($year) === 2) {
				$year = "20" . $year;
			}
			return [$month, $year];
		} elseif(strlen($date) == 4) {
			$month = substr($date, 0, 2);
			$year = "20". substr($date, 2, 2);
		}
		return [$month, $year];
	}
}
