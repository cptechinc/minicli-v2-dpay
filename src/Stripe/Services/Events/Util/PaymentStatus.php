<?php namespace Dpay\Stripe\Services\Events\Util;
// Dpay
use Dpay\Util\PaymentStatus as TargetStatus;

enum PaymentStatus: string
{
    case Declined = TargetStatus::Declined->value;
    case Paid     = TargetStatus::Paid->value;
    case Unpaid   = TargetStatus::Unpaid->value;

	/**
	 * Return Status
	 * @param  string $status
	 * @return PaymentStatus
	 */
	public static function findDpayStatus(string $status) : PaymentStatus
    {
		return match($status) {
            'declined' => self::Declined,
            'paid'     => self::Paid,
            'unpaid'   => self::Unpaid,
		};
	}
}
