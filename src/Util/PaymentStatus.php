<?php namespace Dpay\Util;

enum PaymentStatus: string
{
    case Canceled        = 'canceled';
    case Declined        = 'declined';
    case Paid            = 'paid';
    case Unpaid          = 'unpaid';
}
