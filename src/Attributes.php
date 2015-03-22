<?php

namespace rock\sanitize;


use rock\base\ObjectInterface;
use rock\base\ObjectTrait;
use rock\helpers\ArrayHelper;

class Attributes implements ObjectInterface
{
    use ObjectTrait;

    public $remainder;
    public $attributes = [];

    public function sanitize($input)
    {
        $object = null;
        if (is_object($input)) {
            $object = $input;
            $input = (array)$input;
        }
        if ($this->attributes instanceof Sanitize) {
            $this->each($input);
        }
        $result = [];
        foreach ($this->attributes as $attribute => $sanitize) {
            if (!$sanitize instanceof Sanitize) {
                throw new SanitizeException("`{$attribute}` is not `".Sanitize::className()."`");
            }
            if ($attribute === $this->remainder) {
                $result = array_merge($result, $this->remainder($sanitize, $input));
                continue;
            }
            if (!isset($input[$attribute])) {
                continue;
            }
            $result[$attribute] = $sanitize->sanitize($input[$attribute]);
        }

        $result = is_int(key($result)) ? $result + $input : array_merge($input, $result);
        if (isset($object)) {
            foreach ($result as $property => $value) {
                $object->$property = $value;
            }
            return $object;
        }
        return $result;
    }

    protected function each($input)
    {
        $sanitize = $this->attributes;
        $this->attributes = [];
        foreach($input as $key => $value) {
            $this->attributes[$key] = $sanitize;
        }
    }

    protected function remainder(Sanitize $sanitize, $input)
    {
        $input = ArrayHelper::diffByKeys($input, array_keys($this->attributes));
        return (new static(['attributes' => $sanitize]))->sanitize($input);
    }
}