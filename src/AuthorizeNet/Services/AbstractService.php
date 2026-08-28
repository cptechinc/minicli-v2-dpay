<?php namespace Dpay\AuthorizeNet\Services;

// Dpay
use Dpay\AuthorizeNet\ApiClient;
use Dpay\AuthorizeNet\Config;

/**
 * Template for API Services for Authorize.net
 *
 * @property string $errorMsg Error Message
 */
abstract class AbstractService
{
    public string $errorMsg = '';
    protected Config $config;

    public function __construct()
    {
        $this->config = Config::instance();
    }

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
