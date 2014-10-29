<?php

namespace rock\sanitize\rules;


class UpperFirst extends Rule
{
    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        return is_string($input) ? \rock\helpers\String::upperFirst($input) : $input;
    }
} 