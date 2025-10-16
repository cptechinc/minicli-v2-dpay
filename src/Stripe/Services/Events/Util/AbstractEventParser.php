<?php namespace Dpay\Stripe\Services\Events\Util;
// Stripe API Library
use Stripe\Event as StripeEvent;
// Dpay
use Dpay\Data\Event as DpayEvent;


abstract class AbstractEventParser {
    const EVENTS = [];

    public static function canParseEvent(string $code) : bool
    {
        return in_array($code, static::EVENTS);
    }

    public static function parse(StripeEvent $sEvent) : DpayEvent
    {
        if (static::canParseEvent($sEvent->type) === false) {
            return new DpayEvent();
        }
        return static::parseEvent($sEvent);
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