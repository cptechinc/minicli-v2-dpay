<?php namespace Dpay\PayTrace\Services;

// Dpay
use Dpay\PayTrace\Requests;

/**
 * Template for API Services for PayTrace
 *
 * @property string $errorMsg Error Message
 * @property string $token    Oauth Token
 */
abstract class AbstractService
{
    public string $errorMsg = '';
    protected string $token;

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
     * Return if connection to API was made
     */
    public function connect(): bool
    {
        return $this->initOauth();
    }

    /* =============================================================
        Contract functions
    ============================================================= */
    /**
     * Process Request
     */
    abstract public function process(): bool;

    /* =============================================================
        API Requests
    ============================================================= */
    /**
     * Send Oauth Request to get Token
     */
    private function initOauth(): bool
    {
        $auth = new Requests\Oauth();
        $hasToken = $auth->generate();

        if ($hasToken === false) {
            return false;
        }
        $this->token = $auth->getToken();
        return true;
    }
}
