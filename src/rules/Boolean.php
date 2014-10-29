<?php

namespace rock\sanitize\rules;


class Boolean extends Rule
{
    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        return (bool)$input;
    }
}