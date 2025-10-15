<?php namespace Dpay\Data;

/**
 * @property string  $id
 * @property string  $type
 * @property string  $apitype
 * @property int     $timestamp
 * @property Payment $object
 * @property array   $apidata
 */
class Event extends Data {
    public function getArray() : array
    {
        $data = $this->data;
        $data['object'] = $this->object->getArray();
        return $data;
    }
}