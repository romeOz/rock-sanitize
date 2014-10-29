<?php

namespace rock\sanitize\rules;


class Int extends Rule
{
    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        return (int)$input;
    }
} 