<?php

namespace rock\sanitize;

use rock\helpers\String;

class Exception extends \Exception
{
    use ClassName;

    const UNKNOWN_CLASS = 'Unknown class: {class}';
    const UNKNOWN_METHOD = 'Unknown method: {method}';
    const NOT_UNIQUE  = 'Keys must be unique: {data}';
    const INVALID_SAVE = 'Cache invalid save by key: {key}';
    const UNKNOWN_FILE = 'Unknown file: {path}';
    const FILE_EXISTS = 'File exists: {path}';
    const NOT_CREATE_FILE = 'Does not create file: {path}';

    const UNKNOWN_SNIPPET = 'Unknown snippet: {name}';
    const UNKNOWN_FILTER = 'Unknown filter: {name}';
    const UNKNOWN_PARAM_FILTER = 'Unknown param filter: {name}';

    /**
     * @param string     $msg
     * @param array      $placeholders
     * @param \Exception $handler
     */
    public function __construct($msg, array $placeholders = [], \Exception $handler = null)
    {
        $msg = String::replace($msg, $placeholders);
        parent::__construct($msg, 0, $handler);
    }

} 