<?php namespace Dpay\Stripe\Endpoints;

// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\Token as StripeToken;
// Dpay
use Dpay\Stripe\ApiClient;
use Dpay\Stripe\Data\CreditCards\CreditCardRequest as CardRequest;

/**
 * Wrapper for Stripe API to interface with the Tokens Endpoint
 */
class Tokens extends AbstractEndpoint
{
    #[\Override]
    public static string $errorMsg;

    /* =============================================================
           Create
       ============================================================= */
    /**
     * Create Credit Card Token
     */
    public static function createCard(CardRequest $rqst): StripeToken
    {
        $stripe = ApiClient::instance();
        $data   = $rqst->toApiArray();

        try {
            $token = $stripe->tokens->create(['card' => $data]);
        } catch (ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return new StripeToken();
        }
        return $token;
    }
}
