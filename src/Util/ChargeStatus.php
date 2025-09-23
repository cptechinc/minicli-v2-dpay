<?php namespace Dpay\Util;

enum ChargeStatus: string
{
    case None     = '';
    case Refunded = 'refunded';
    case Voided   = 'canceled';
    case RequiresCapture = 'requires_capture';
    case RequiresConfirmation = 'requires_confirmation';
    case Captured = 'succeeded';
}
