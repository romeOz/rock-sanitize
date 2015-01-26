<?php
if (!function_exists('boolval')) {
    function boolval($val) {
        return (bool)$val;
    }
}