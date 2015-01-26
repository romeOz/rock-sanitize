<?php

namespace rock\sanitize\rules;

use rock\sanitize\SanitizeException;

class Call extends Rule
{
    protected $call;
    protected $args = [];

    public function __construct($call, array $args = null, $config = [])
    {
        if (!is_callable($call) && !function_exists($call)) {
            throw new SanitizeException('Invalid call.');
        }
        $this->parentConstruct($config);
        $this->call = $call;
        if (!empty($args)) {
            $this->args = $args;
        }
    }

    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        $args = $this->args;
        array_unshift($args, $input);
        return call_user_func_array($this->call, $args);
    }
}