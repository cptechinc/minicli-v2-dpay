<?php namespace Dpay\AuthorizeNet;
// AuthorizeNet Library
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\contract\v1\CreditCardType;
use net\authorize\api\contract\v1\CreditCardTrackType;
use net\authorize\api\contract\v1\CustomerAddressType;
use net\authorize\api\contract\v1\CustomerDataType;
use net\authorize\api\contract\v1\OrderType;
use net\authorize\api\contract\v1\PaymentType;
use net\authorize\api\contract\v1\SettingType;
use net\authorize\api\contract\v1\TransRetailInfoType;
// Dplus Payments Model
use Payment;

/**
 * TransactionData
 * 
 * Returns Authorize.Net SDK objects parsed from request data
 */
class TransactionData {
	const MARKET_TYPE_RETAIL = '2';
	const MARKET_TYPE_ECOMM  = '0';
	const DEVICE_TYPE_PC     = '5';
	
	/**
	 * Return API Customer Address Object
	 * @param  Payment $payRequest  Payment Request Data
	 * @return CustomerAddressType
	 */
	public static function customerAddressType(Payment $payRequest) : CustomerAddressType
	{
		// Set the customer's Bill To address
		$name = explode(' ', $payRequest->getCardName());
		$address = new CustomerAddressType();
		$address->setFirstName($name[0]);
		$address->setLastName($name[1]);
		$address->setCompany($payRequest->getCustid());
		$address->setAddress($payRequest->getStreet());
		$address->setZip($payRequest->getZipcode());
		$address->setCountry("USA");
		return $address;
	}

	/**
	 * Return API Customer Data Object
	 * @param  Payment $payRequest  Payment Request Data
	 * @return CustomerDataType
	 */
	public static function customerDataType(Payment $payRequest) : CustomerDataType
	{
		$customer = new CustomerDataType();
		$customer->setType("individual");
		$customer->setId($payRequest->getCustid());
		// $customer->setEmail("EllenJohnson@example.com");
		return $customer;
	}

	/**
	 * Return API OrderType
	 * @param  Payment $payRequest  Payment Request Data
	 * @return OrderType
	 */
	public static function orderType(Payment $payRequest) : OrderType
	{
		$order = new AnetAPI\OrderType();
		$order->setInvoiceNumber($payRequest->getOrdernbr());
		return $order;
	}

	/**
	 * Return API PaymentType
	 * @param  Payment $payRequest  Payment Request Data
	 * @return PaymentType
	 */
	public static function paymentType(Payment $payRequest) : PaymentType {
		$payment = new PaymentType();

		if ($payRequest->is_card_present()) {
			$payment->setTrackData(self::creditCardTracktype($payRequest));
			return $payment;
		}

		$payment->setCreditCard(self::creditCardType($payRequest));
		return $payment;
	}

	/**
	 * Return API CreditCardTrackype
	 * @param  Payment $payRequest  Payment Request Data
	 * @return CreditCardTrackType
	 */
	public static function creditCardTracktype(Payment $payRequest) : CreditCardTrackType
	{
		$card = new CreditCardTrackType();
		$card->setTrack2($payRequest->track2());
		return $card;
	}

	/**
	 * Return API CreditCardType
	 * @param  Payment $payRequest  Payment Request Data
	 * @return CreditCardType
	 */
	public static function creditCardType(Payment $payRequest) : CreditCardType {
		$card = new CreditCardType();
		$card->setCardNumber($payRequest->cardnumber());
		$card->setExpirationDate(self::parseCardExpireDate($payRequest->expiredate()));
		$card->setCardCode($payRequest->cvv());
		return $card;
	}

	/**
	 * Return API TransRetailInfoType
	 * NOTE: It depends if Track2 data is supplied
	 * @param  Payment $payRequest  Payment Request Data
	 * @return TransRetailInfoType
	 */
	public static function transRetailInfoType(Payment $payRequest) : TransRetailInfoType
	{
		$retail = new TransRetailInfoType();

		if ($payRequest->is_card_present()) {
			$retail->setMarketType(self::MARKET_TYPE_RETAIL); //RETAIL CP
			$retail->setDeviceType(self::DEVICE_TYPE_PC);     //PC REGISTER
			return $retail;
		}
		$retail->setMarketType(self::MARKET_TYPE_ECOMM);
		return $retail;
	}

	/**
	 * Return API SettingType
	 * @param  Payment $payRequest  Payment Request Data
	 * @return SettingType
	 */
	public static function settingType() : SettingType
	{
		$settings = new SettingType();
		$settings->setSettingName("duplicateWindow");
		// $settings->setSettingValue("60");
		$settings->setSettingValue("15");
		return $settings;
	}

	/**
	 * Return Date converted into AuthorizeNet format (YYYY-MM)
	 * @param  string $date
	 * @return string
	 */
	private static function parseCardExpireDate(string $date) : string
	{
		if (strpos($date, '/') !== FALSE) {
			$datearray = explode('/', $date);
			$month = $datearray[0];
			$year  = $datearray[1];

			if (strlen($year) === 2) {
				$year = "20" . $year;
			}
		} elseif(strlen($date) == 4) {
			$month = substr($date, 0, 2);
			$year = "20". substr($date, 2, 2);
		}
		return "$year-$month";
	}
}