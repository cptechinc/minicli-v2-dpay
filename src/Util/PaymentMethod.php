<?php namespace Dpay\Util;

enum PaymentMethod: string
{
    case Ach            = 'ach';
    case AmazonPay      = 'amazonpay';
    case CashApp        = 'cashapp';
    case CreditCard     = 'creditcard';
    case MobilePay      = 'mobile';
    case PayPal         = 'paypal';
}
