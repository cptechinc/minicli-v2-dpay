<?php namespace Dpay\AuthorizeNet\Transactions;

/**
 * Transactions\CapturePreauthorized
 *
 * Charges previously pre-authorized Credit Card
 */
class CapturePreauthorized extends AbstractTransaction {
	const TYPE = 'priorAuthCaptureTransaction';
}
