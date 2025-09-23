<?php namespace Dpay\Util;

enum ChargeStatus: string
{
    case Captured            = 'succeeded';
    case Declined             = 'declined';
    case None                 = '';
    case Refunded             = 'refunded';
    case RequiresCapture      = 'requires_capture';
    case RequiresConfirmation = 'requires_confirmation';
    case Voided               = 'canceled';
}
