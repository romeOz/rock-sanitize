<?php

namespace rock\sanitize\rules;


class Ip extends Rule
{
    protected $normalize = true;

    public function __construct($normalize = true, $config = [])
    {
        $this->parentConstruct($config);
        $this->normalize = $normalize;
    }

    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        if (strpos($input, '/') !== false) {
            return $this->rangeUsingCIDR($input);
        } elseif (strpos($input, '*') !== false) {
            return $this->rangeUsingWildcards($input);
        }
        return $this->normalize($input);
    }

    /**
     * @link http://stackoverflow.com/a/10086404
     * @param string $input
     * @return string
     */
    protected function rangeUsingCIDR($input)
    {
        list($ip, $prefix) = explode('/', $input);
        if ($this->isIPv6($ip)) {

            if (strpos($prefix, ':') !== false) {
                $prefix =  strlen(str_replace('0', '', $this->IPv6toBit(inet_pton($prefix))));
            }
            $minBin = inet_pton($ip);
            $min = inet_ntop($minBin);
            $maxBin = $minBin = unpack('H*', $minBin)[1];
            $prefix = 128 - $prefix;
            $pos = 31;
            while ($prefix > 0) {
                $orig = substr($maxBin, $pos, 1);
                $origval = hexdec($orig);
                $newval = $origval | (pow(2, min(4, $prefix)) - 1);
                $new = dechex($newval);
                $maxBin = substr_replace($maxBin, $new, $pos, 1);
                $prefix -= 4;
                $pos -= 1;
            }

            $max = inet_ntop(pack('H*', $maxBin));

            return $this->normalize($min) . '-' . $this->normalize($max);

        }
        if (strpos($prefix, '.') !== false) {
            $prefix =  strlen(str_replace('0', '', sprintf('%032b', ip2long($prefix))));
        }
        return  long2ip((ip2long($ip)) & ((-1 << (32 - (int)$prefix))))
        . '-'
        . long2ip((ip2long($ip)) + pow(2, (32 - (int)$prefix)) - 1);
    }

    protected function rangeUsingWildcards($input)
    {
        $input = $this->fillAddress($input);

        if (strpos($input, ':') !== false) {
            return  $this->normalize(strtr($input, '*', '0')) . '-' . $this->normalize(str_replace('*', 'ffff', $input));
        }
        return strtr($input, '*', '0') . '-' . str_replace('*', '255', $input);
    }

    protected function isIPv6($ip)
    {
        return (bool)filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    /**
     * @link http://stackoverflow.com/a/7951507
     * @param string $ip
     * @return string
     */
    protected function IPv6toBit($ip)
    {
        $result = '';
        $ip = str_split(unpack('A16', $ip)[1]);
        foreach ($ip as $char) {
            $result .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }
        return $result;
    }
    protected function fillAddress($input, $char = '*')
    {
        if (strpos($input, ':') !== false) {
            while (substr_count($input, ':') < 7) {
                $input .= ':' . $char;
            }
            return $input;
        }
        while (substr_count($input, '.') < 3) {
            $input .= '.' . $char;
        }
        return $input;
    }

    protected function normalize($ip)
    {
        if (!$this->normalize) {
            return $ip;
        }
        return inet_ntop(inet_pton($ip));
    }
} 