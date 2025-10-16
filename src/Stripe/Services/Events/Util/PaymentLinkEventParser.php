<?php namespace Dpay\Stripe\Services\Events\Util;
// Stripe API Library
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Event as StripeEvent;
// Dpay
use Dpay\Data\Event as DpayEvent;
use Dpay\Data\Payment as DpayPayment;
use Dpay\Stripe\Endpoints;
use Dpay\Stripe\Services\PaymentLinks\Util\PaymentMethod;

class PaymentLinkEventParser extends AdefaultEventParser {
    const EVENTS = [
        'checkout.session.async_payment_succeeded',
        'checkout.session.async_payment_failed'
    ];

    const EVENTS_FAILED = [
        'checkout.session.async_payment_failed',
    ];

    public static function parseEvent(StripeEvent $sEvent) : DpayEvent
    {
        if (static::canParseEvent($sEvent->type) === false) {
            return AdefaultEventParser::parseEvent($sEvent);
        }
        $data = AdefaultEventParser::parseEvent($sEvent);
		$data->type    = 'payment';

        /** @var StripeCheckoutSession */
        $checkout = $sEvent->data->object;

        $payment = new DpayPayment();
		$payment->id            = $checkout->payment_link;
		$payment->transactionid = $checkout->payment_intent;
		$payment->type          = 'paymentlink';
        $payment->custid        = $checkout->metadata->offsetGet('custid') ?? '';
		$payment->ordernbr      = $checkout->metadata->offsetGet('ordernbr') ?? '';
        $payment->method        = PaymentMethod::findDpayMethod($checkout->payment_method_types[0])->value;
		$payment->success       = $checkout->payment_status == 'paid';
        $payment->status        = PaymentStatus::findDpayStatus($checkout->payment_status)->value;
        $payment->amount        = $checkout->amount_total / 100;

        foreach ($checkout->metadata->toArray() as $key => $value) {
			$payment->metadata->set($key, $value);
		}

        if ($payment->success === false) {
			$charge = Endpoints\Charges::fetchById($payment->transactionid);
			$payment->errorCode = $charge->last_payment_error->code;
			$payment->errorMsg  = $charge->last_payment_error->message;
		}
        if ($payment->success === false && $payment->errorCode) {
            $payment->status = PaymentStatus::Declined->value;
        }
        $data->object = $payment;
        return $data;
    }
}