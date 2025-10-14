<?php namespace Dpay\Util;

enum PaymentStatus: string
{
    case Declined        = 'declined';
    case Paid            = 'paid';
    case Unpaid          = 'unpaid';
}
