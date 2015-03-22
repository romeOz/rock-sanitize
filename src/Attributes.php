<?php

namespace rock\sanitize;


use rock\base\ObjectInterface;
use rock\base\ObjectTrait;
use rock\helpers\ArrayHelper;

class Attributes implements ObjectInterface
{
    use ObjectTrait;

    public $remainder;
    public $recursive = true;
    public $attributes = [];

    public function sanitize($input)
    {
        $object = null;
        $attributes = $this->attributes;
        if (is_object($input)) {
            $object = $input;
            $input = (array)$input;
        }
        if ($attributes instanceof Sanitize) {
            $attributes = $this->each($attributes, $input);
        }
        $result = [];
        foreach ($attributes as $attribute => $sanitize) {
            if (!$sanitize instanceof Sanitize) {
                throw new SanitizeException("`{$attribute}` is not `".Sanitize::className()."`");
            }
            if ($attribute === $this->remainder) {
                $result = array_merge($result, $this->remainder($sanitize, $attributes, $input));
                continue;
            }
            if (!isset($input[$attribute])) {
                continue;
            }
            if ((is_array($input[$attribute]) || is_object($input[$attribute]))) {
                if (!$this->recursive) {
                    continue;
                }
                $config = [
                    'remainder' => $this->remainder,
                    'recursive' => $this->recursive,
                    'attributes' => $this->attributes
                ];
                $this->setProperties($config);
                $result[$attribute] = $this->sanitize($input[$attribute]);
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

    protected function each(Sanitize $sanitize, $input)
    {
        $attributes = [];
        foreach($input as $key => $value) {
            $attributes[$key] = $sanitize;
        }
        return $attributes;
    }

    protected function remainder(Sanitize $sanitize, array $attributes, $input)
    {
        $input = ArrayHelper::diffByKeys($input, array_keys($attributes));
        $config = [
            'remainder' => $this->remainder,
            'recursive' => $this->recursive,
            'attributes' => $sanitize
        ];
        $this->setProperties($config);
        return $this->sanitize($input);
    }
}