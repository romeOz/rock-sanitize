<?php

namespace rock\sanitize\rules;

use rock\sanitize\ObjectTrait;

abstract class Rule
{
    use ObjectTrait{
        ObjectTrait::__construct as parentConstruct;
    }

    /**
     * @param mixed $input
     * @return bool
     */
    abstract public function sanitize($input);
} 