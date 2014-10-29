<?php

namespace rock\sanitize\rules;

use rock\sanitize\Exception;

class Call extends Rule
{
    protected $call;
    protected $args = [];

    public function __construct($call, array $args = null, $config = [])
    {
        if (!is_callable($call) && !function_exists($call)) {
            throw new Exception('Invalid call.');
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
        array_unshift($this->args, $input);
        return call_user_func_array($this->call, $this->args);
    }
}