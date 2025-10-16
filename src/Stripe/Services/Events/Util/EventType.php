<?php namespace Dpay\Stripe\Services\Events\Util;

enum EventType: string
{
	case CheckoutPaymentDeclined = 'checkout.session.async_payment_failed';
    case CheckoutPaymentSuccess = 'checkout.session.async_payment_succeeded';

	/**
	 * Return Status
	 * @param  string $type
	 * @return EventType
	 */
	public static function find(string $type) : EventType|false
    {
		return match($type) {
            'checkout.session.async_payment_failed'    => self::CheckoutPaymentDeclined,
            'checkout.session.async_payment_succeeded' => self::CheckoutPaymentSuccess,
			default => false,
		};
	}

}
