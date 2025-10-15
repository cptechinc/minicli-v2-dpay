<?php namespace Dpay\Stripe\Services\PaymentLinks\Util;
// Stripe SDK
use Stripe\PaymentMethod as StripePaymentMethod;
// Dpay
use Dpay\Util\PaymentMethod as TargetMethod;

enum PaymentMethod: string
{
	case Ach			= TargetMethod::Ach->value;
	case AmazonPay		= TargetMethod::AmazonPay->value;
	case CashApp		= TargetMethod::CashApp->value;
	case CreditCard 	= TargetMethod::CreditCard->value;
	case MobilePay   	= TargetMethod::MobilePay->value;
	case PayPal 		= TargetMethod::PayPal->value;

	/**
	 * Return Status
	 * @param  string $status
	 * @return PaymentMethod
	 */
	public static function findDpayMethod(string $status) : PaymentMethod
    {
		return match($status) {
            StripePaymentMethod::TYPE_AMAZON_PAY	   => self::AmazonPay,
            StripePaymentMethod::TYPE_CARD             => self::CreditCard,
            StripePaymentMethod::TYPE_CASHAPP          => self::CashApp,
            StripePaymentMethod::TYPE_MOBILEPAY        => self::MobilePay,
            StripePaymentMethod::TYPE_PAYPAL           => self::PayPal,
			StripePaymentMethod::TYPE_US_BANK_ACCOUNT  => self::Ach,
			default 			                       => self::CreditCard
		};
	}
}
