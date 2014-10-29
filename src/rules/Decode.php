<?php

namespace rock\sanitize\rules;

class Decode extends Rule
{
    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        return is_string($input) ? \rock\helpers\String::decode($input) : $input;
    }
} 