<?php

namespace RandBuilder;

class Rander
{
    private static $number_list = "0123456789";
    private static $alpha_list = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";

    public static function rand_string($length, $prefix = '', $end = '', $filter = null): string
    {
        $ret = $prefix;
        $list = self::$number_list . self::$alpha_list;
        $listlen = strlen($list);

        while ($length > 0) {
            $ret .= $list[rand(0, $listlen - 1)];
            $length --;
        }

        $ret .= $end;

        return $filter && is_callable($filter) ? $filter($ret) : $ret;
    }

    public static function rand_int($min, $max): int
    {
        return rand($min, $max);
    }

    public static function rand_intstr($len, $reducer = null): string
    {
        $ret_str = '';

        while ($len > 0) {
            $ret_str .=  self::$number_list[rand(0, 9)];
            $len --;
        }

        return is_callable($reducer) ? $reducer($ret_str) : $ret_str;
    }

    public function rand_float($range, $precision = 0): float
    {
        $min = $range[0];
        $max = $range[1];

        $ret = round(rand($min, $max) + rand($min, $max) / rand($min, $max), $precision);

        $diff = $ret - $max;

        return $diff > 0 ? $max - $diff : $ret;
    }

    public static function max_string($len): int
    {
        $len = is_array($len) ? $len[1] : $len;
        $ret = pow(strlen(self::$number_list.self::$alpha_list), $len);

        return $ret > PHP_INT_MAX ? PHP_INT_MAX : $ret;
    }

    public static function max_intstr($len): int
    {
        return pow(strlen(self::$number_list), $len);
    }

    public static function max_float($range, $precision)
    {
        return ($range[1] - $range[0]) * pow(10, $precision);
    }
}

?>
