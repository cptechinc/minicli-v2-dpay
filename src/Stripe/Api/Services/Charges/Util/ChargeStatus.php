<?php namespace Dpay\Stripe\Api\Services\Charges\Util;
// Dpay
use Dpay\Util\ChargeStatus as TargetStatus;

enum ChargeStatus: string
{
    case None                 = TargetStatus::None->value;
    case Refunded             = TargetStatus::Refunded->value;
    case Voided               = TargetStatus::Voided->value;
    case RequiresCapture      = TargetStatus::RequiresCapture->value;
    case RequiresConfirmation = TargetStatus::RequiresConfirmation->value;
    case Captured             = TargetStatus::Captured->value;

    /**
     * Return Status
     * @param  string $status
     * @return ChargeStatus
     */
    public static function find(string $status) : ChargeStatus {
        return match($status) {
            'canceled'              => self::Voided,
            'refunded'              => self::Refunded,
            'requires_capture'      => self::RequiresCapture,
            'requires_confirmation' => self::RequiresConfirmation,
            'succeeded'             => self::Captured,
            default                 => self::None
        };
    }
}
