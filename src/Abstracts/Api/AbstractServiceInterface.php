<?php namespace Dpay\Abstracts\Api;

/**
 * Interface for API Services
 */
interface AbstractServiceInterface {

/* =============================================================
    Contract functions
============================================================= */
    public function connect() : bool;
    
    public function process() : bool;
}