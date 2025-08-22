<?php namespace Dpay\PayTrace;

/**
 * Environment
 * Holds constants for PayTrace API
 */
class Environment {
	const OAUTH_GRANT_TYPE = 'password';
	const API_URL = "https://api.paytrace.com";
	const API_VERSION = 'v1';
	const ENDPOINT_OAUTH = '/oauth/token';
}
