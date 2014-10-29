<?php

namespace rock\sanitize\rules;


class String extends Rule
{
    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        return (string)$input;
    }
} 