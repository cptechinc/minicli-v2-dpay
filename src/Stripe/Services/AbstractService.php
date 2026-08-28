<?php namespace Dpay\Stripe\Services;

// Lib
use Dpay\Stripe\ApiClient;

/**
 * Template for API Services for Stripe
 *
 * @property string $errorMsg Error Message
 */
abstract class AbstractService
{
    public const ENABLED = true;

    public string $errorMsg = '';

    /* =============================================================
            Getters
        ============================================================= */
    /**
     * Return if Error has occurred
     */
    public function hasError(): bool
    {
        return empty($errorMsg) === false;
    }

    /**
     * Return API client
     */
    public function api(): ApiClient
    {
        return ApiClient::instance();
    }

    /**
     * Return if connection to API was made
     */
    public function connect(): bool
    {
        return $this->api()->connect();
    }

    /* =============================================================
            Contract functions
        ============================================================= */
    /**
     * Process Request
     */
    abstract public function process(): bool;
}
