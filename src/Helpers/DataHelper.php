<?php

namespace Owl\Common\Helpers;

use ArrayAccess;
use Closure;

class DataHelper
{
    public static function isAccessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    public static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }

    /**
     * Set value by path
     *
     * @param $target
     * @param $key
     * @param $value
     * @param bool $overwrite
     * @return array
     */
    public static function set(&$target, $key, $value, $overwrite = true)
    {
        $segments = is_array($key) ? $key : explode('.', $key);

        if (($segment = array_shift($segments)) === '*') {
            if ( ! self::isAccessible($target)) {
                $target = [];
            }

            if ($segments) {
                foreach ($target as &$inner) {
                    self::set($inner, $segments, $value, $overwrite);
                }
            } elseif ($overwrite) {
                foreach ($target as &$inner) {
                    $inner = $value;
                }
            }
        } elseif (self::isAccessible($target)) {
            if ($segments) {
                if ( ! isset($target[$segment])) {
                    $target[$segment] = [];
                }

                self::set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite || ! isset($target[$segment])) {
                $target[$segment] = $value;
            }
        } elseif (is_object($target)) {
            if ($segments) {
                if ( ! isset($target->{$segment})) {
                    $target->{$segment} = [];
                }

                self::set($target->{$segment}, $segments, $value, $overwrite);
            } elseif ($overwrite || ! isset($target->{$segment})) {
                $target->{$segment} = $value;
            }
        } else {
            $target = [];

            if ($segments) {
                self::set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite) {
                $target[$segment] = $value;
            }
        }

        return $target;
    }

    /**
     * Get data by path
     *
     * @param $target
     * @param $key
     * @param null $default
     * @return array|mixed
     */
    public static function get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while ( ! is_null($segment = array_shift($key))) {
            if ($segment === '*') {
                if ( ! is_array($target)) {
                    return self::value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = self::get($item, $key);
                }

                return in_array('*', $key) ? self::collapse($result) : $result;
            }

            if (self::isAccessible($target) && self::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return self::value($default);
            }
        }

        return $target;
    }

    public static function exists($target, $path)
    {
        return isset($target[$path]) || isset($target->$path);
    }

    public static function collapse($array)
    {
        $results = [];

        foreach ($array as $values) {
            if ( ! is_array($values)) {
                continue;
            }

            $results = array_merge($results, $values);
        }

        return $results;
    }
}