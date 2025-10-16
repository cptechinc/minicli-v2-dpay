<?php namespace Dpay\Stripe\Services\Events\Util;
// Stripe API Library
use Stripe\Event as StripeEvent;
// Dpay
use Dpay\Data\Event as DpayEvent;

class AdefaultEventParser extends AbstractEventParser {
    public static function canParseEvent(string $code) : bool
    {
        return true;
    }

    public static function parseEvent(StripeEvent $sEvent) : DpayEvent
    {
        $data = new DpayEvent();
        $data->id        = $sEvent->id;
		$data->type      = $sEvent->type;
        $data->apitype   = $sEvent->type;
        $data->timestamp = $sEvent->created;
        $data->apidata   = $sEvent->toArray();
        return $data;
    }
}