<?php

namespace rock\sanitize\rules;


use rock\helpers\Numeric;

class Negative extends Rule
{
    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        $input = Numeric::toNumeric($input);
        return  $input > 0 ? 0 : $input;
    }
} 