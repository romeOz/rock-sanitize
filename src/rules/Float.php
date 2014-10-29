<?php

namespace rock\sanitize\rules;


class Float extends Rule
{
    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        return (float)$input;
    }
} 