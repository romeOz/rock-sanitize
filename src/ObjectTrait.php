<?php

namespace rock\sanitize;

trait ObjectTrait
{
    use ClassName;

    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            $this->setProperties($config);
        }
    }

    /**
     * Configures an object with the initial property values.
     *
     * @param array  $properties the property initial values given in terms of name-value pairs.
     */
    public function setProperties(array $properties)
    {
        foreach ($properties as $name => $value) {
            $this->$name = $value;
        }
    }
} 