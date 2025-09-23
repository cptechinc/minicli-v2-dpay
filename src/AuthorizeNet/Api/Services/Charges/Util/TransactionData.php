<?php namespace Dpay\AuthorizeNet\Api\Services\Charges\Util;
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
// Dpay
use Dpay\Data\Charge;

/**
 * Returns Authorize.Net SDK objects parsed from request data
 */
class TransactionData {
	const MARKET_TYPE_RETAIL = '2';
	const MARKET_TYPE_ECOMM  = '0';
	const DEVICE_TYPE_PC     = '5';
	const TRANSACTION_STATUSES = [
		'pending-settlement' => 'capturedPendingSettlement'
	];
	
	/**
	 * Return API Customer Address Object
	 * @param  Charge $charge
	 * @return CustomerAddressType
	 */
	public static function customerAddressType(Charge $charge) : CustomerAddressType
	{
		// Set the customer's Bill To address
		$name = explode(' ', $charge->card->name);
		$address = new CustomerAddressType();
		$address->setFirstName($name[0]);
		$address->setLastName($name[1]);
		$address->setCompany($charge->custid);
		$address->setAddress($charge->card->address1);
		$address->setZip($charge->card->zipcode);
		$address->setCountry("USA");
		return $address;
	}

	/**
	 * Return API Customer Data Object
	 * @param  Charge $charge
	 * @return CustomerDataType
	 */
	public static function customerDataType(Charge $charge) : CustomerDataType
	{
		$customer = new CustomerDataType();
		$customer->setType("individual");
		$customer->setId($charge->custid);
		// $customer->setEmail("EllenJohnson@example.com");
		return $customer;
	}

	/**
	 * Return API OrderType
	 * @param  Charge $charge
	 * @return OrderType
	 */
	public static function orderType(Charge $charge) : OrderType
	{
		$order = new AnetAPI\OrderType();
		$order->setInvoiceNumber($charge->ordernbr);
		return $order;
	}

	/**
	 * Return API PaymentType
	 * @param  Charge $charge  Payment Request Data
	 * @return PaymentType
	 */
	public static function paymentType(Charge $charge) : PaymentType
	{
		$payment = new PaymentType();

		if ($charge->card->hasTrack2()) {
			$payment->setTrackData(self::creditCardTracktype($charge));
			return $payment;
		}

		$payment->setCreditCard(self::creditCardType($charge));
		return $payment;
	}

	/**
	 * Return API CreditCardTrackype
	 * @param  Charge $charge  Payment Request Data
	 * @return CreditCardTrackType
	 */
	public static function creditCardTracktype(Charge $charge) : CreditCardTrackType
	{
		$card = new CreditCardTrackType();
		$card->setTrack2($charge->card->track2);
		return $card;
	}

	/**
	 * Return API CreditCardType
	 * @param  Charge $charge  Payment Request Data
	 * @return CreditCardType
	 */
	public static function creditCardType(Charge $charge) : CreditCardType
	{
		$expdate = $charge->card->expiredateYear() . '-' . $charge->card->expiredateMonth();
		$card = new CreditCardType();
		$card->setCardNumber($charge->card->cardnbr);
		$card->setExpirationDate($expdate);
		$card->setCardCode($charge->card->cvc);
		return $card;
	}

	/**
	 * Return API TransRetailInfoType
	 * NOTE: It depends if Track2 data is supplied
	 * @param  Charge $charge  Payment Request Data
	 * @return TransRetailInfoType
	 */
	public static function transRetailInfoType(Charge $charge) : TransRetailInfoType
	{
		$retail = new TransRetailInfoType();

		if ($charge->card->hasTrack2()) {
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
}