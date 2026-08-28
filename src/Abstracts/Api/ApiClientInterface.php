<?php namespace Dpay\Abstracts\Api;

/**
 * Interface for API clients
 */
interface ApiClientInterface
{
    public static function instance(): static;

    public function connect(): bool;
}
