<?php namespace Dpay\PayTrace;

/**
 * Holds constants for PayTrace API
 */
class Environment
{
    public const OAUTH_GRANT_TYPE = 'password';
    public const API_URL = 'https://api.paytrace.com';
    public const API_VERSION = 'v1';
    public const ENDPOINT_OAUTH = '/oauth/token';
}
