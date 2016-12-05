<?php

namespace RandBuilder;

class Builder
{
    /**
     * Save unique values;
     */
    private static $values = [];

    public static function build(Array $info): Array
    {
        $ret = [];

        $count = $info['count'] ?? 1;
        $object = $info['object'];
        $keys = array_keys($object);

        $max = $count;

        foreach ($keys as $key) {
            $unique = $object[$key]['unique'] ?? false;
            $type = $object[$key]['type'] ?? 'string';

            if ($type === 'string') {
                $max = $unique ? Rander::max_string($object[$key]['length'] ?? 1) : $max;
            } else if ($type === 'integer') {
                $range = $object[$key]['range'] ?? [0, getrandmax()];
                $max = $unique ? $range[1] - $range[0] : $max;
            } else {
                $range = $object[$key]['range'] ?? [0, getrandmax()];
                $precision = $object[$key]['precision'] ?? 0;
                $max = $unique ? Rander::max_float($range, $precision) : $max;
            }
            $count = $max < $count ? $max : $count;
        }

        for ($i = 0; $i < $count; $i ++) {
            foreach ($keys as $key) {
                $ret[$i][$key] = self::generate($key, $object[$key]);
            }
        }

        return $ret;
    }

    private static function generate($name, $attributes)
    {
        $keys = array_keys($attributes);

        if (isset($keys['type'])) {
            $attributes['type'] = 'string';
        }

        $type = $attributes['type'];

        $ret = null;

        switch ($type) {
            case 'string':
                $ret = self::generate_string($name, $attributes);
                break;
            case 'integer':
                $ret = self::generate_integer($name, $attributes);
                break;
            case 'float':
                $ret = self::generate_float($name, $attributes);
                break;
            default:
                $ret = null;
        }

        return $ret;
    }

    /**
     * @param $key
     * @param $attributes
     * @return string
     */
    private static function generate_string($key, $attributes)
    {
        $length = $attributes['length'] ?? 1;
        $prefix = $attributes['prefix'] ?? '';
        $end = $attributes['end'] ?? '';
        $reducer = $attributes['reducer'] ?? null;
        $unique = $attributes['unique'] ?? false;

        $length = is_array($length) ? rand($length[0], $length[1]) : $length;

        if (!$unique) {
            return Rander::rand_string($length, $prefix, $end, $reducer);
        }

        while (true) {
            $ret = Rander::rand_string($length, $prefix, $end, $reducer);
            if (!in_array($ret, self::$values[$key] ?? [])) {
                return $ret;
            }
            array_push(self::$values[$key], $ret);
        }
    }

    /**
     * @param $key
     * @param $attributes
     * @return int
     */
    private static function generate_integer($key, $attributes)
    {
        $range = $attributes['range'] ?? [0, getrandmax()];
        list($min, $max) = $range;

        $unique = $attributes['unique'] ?? false;

        if ($unique === false) {
            return Rander::rand_int($min, $max);
        }

        self::$values[$key] = self::$values[$key] ?? [];

        while (true) {
            $ret = Rander::rand_int($min, $max);
            if (!in_array($ret, self::$values[$key])) {
                array_push(self::$values[$key], $ret);
                return $ret;
            }

            array_push(self::$values[$key], $ret);
        }
    }

    private static function generate_float($key, $attributes)
    {
        $range = $attributes['range'] ?? [0, getrandmax()];
        $precision = $attributes['precision'];

        return Rander::rand_float($range, $precision);
    }
}
