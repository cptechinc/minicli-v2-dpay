<?php namespace Dpay\AuthorizeNet\Api\Services\Charges\Util;
// Dpay
use Dpay\Util\ChargeStatus as TargetStatus;

enum ChargeStatus: string
{
    case Captured             = TargetStatus::Captured->value;
    case Declined             = TargetStatus::Declined->value;
    case None                 = TargetStatus::None->value;
    case PendingSettlement    = TargetStatus::PendingSettlement->value;
    case Refunded             = TargetStatus::Refunded->value;
    case RequiresCapture      = TargetStatus::RequiresCapture->value;
    case RequiresConfirmation = TargetStatus::RequiresConfirmation->value;
    case Voided               = TargetStatus::Voided->value;
    

    /**
     * Return Status
     * @param  string $status
     * @return ChargeStatus
     */
    public static function find(string $status) : ChargeStatus {
        return match($status) {
            'authorizedPendingCapture'  => self::RequiresCapture,
            'declined'                  => self::Declined,
            'refundPendingSettlement'   => self::Refunded,
            'refundSettledSuccessfully' => self::Refunded,
            'requires_confirmation'     => self::RequiresConfirmation,
            'capturedPendingSettlement' => self::PendingSettlement,
            'settledSuccessfully'       => self::Captured,
            'voided'                    => self::Voided,
            default                     => self::None
        };
    }
}
