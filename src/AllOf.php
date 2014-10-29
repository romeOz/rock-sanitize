<?php

namespace rock\sanitize;


class AllOf
{
    use ObjectTrait {
        ObjectTrait::__construct as parentConstruct;
    }
    /** @var  Sanitize */
    public $sanitize;

    public function __construct($config = [])
    {
        $this->parentConstruct($config);
    }

    public function sanitize($collection)
    {
        if (!$this->sanitize instanceof Sanitize) {
            throw new Exception("`{$this->sanitize}` is not `".Sanitize::className()."`");
        }
        $object = null;
        if (is_object($collection)) {
            $object = $collection;
            $collection = (array)$collection;
        }
        $result = [];
        foreach ($collection as $attribute => $value) {
            $result[$attribute] = $this->sanitize->sanitize($value);
        }
        if (isset($object)) {
            foreach ($result as $property => $value) {
                $object->$property = $value;
            }
            return $object;
        }
        return $result;
    }
}