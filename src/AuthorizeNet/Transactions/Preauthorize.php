<?php namespace Dpay\AuthorizeNet\Transactions;

/**
 * Transactions\Preauthorize
 *
 * Preauthorizes Credit Card for a charge
 */
class Preauthorize extends AbstractTransaction {
	const TYPE = 'authOnlyTransaction';
}
