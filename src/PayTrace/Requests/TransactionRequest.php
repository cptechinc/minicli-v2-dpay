<?php namespace Dpay\PayTrace\Requests;

/**
 * Wrapper for Guzzle HTTP to make Transaction Requests
 */
class TransactionRequest extends AbstractEndpointRequest {
	const ENDPOINTPATH = 'transactions';
}
