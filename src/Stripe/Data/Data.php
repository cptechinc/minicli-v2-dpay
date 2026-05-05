<?php namespace Dpay\Stripe\Data;
// Pauldro
use Pauldro\UtilityBelt\Data\Data as AbstractData;

/**
 * Container For Stripe Data
 */
class Data extends AbstractData {
    /**
     * Return Data in stripe request array format
     * @return array
     */
    public function toApiArray() : array 
    {
       return $this->toArray();
    }
    
    /**
     * Return Data as array
     * @return array
     */
    public function toArray() : array
    {
        return $this->data;
    }
}