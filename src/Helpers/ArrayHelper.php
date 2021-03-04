<?php

namespace Owlcoder\Common\Helpers;

use Closure;

class ArrayHelper
{
    public static function getColumn($array, $column)
    {
        $out = [];

        foreach ($array as $item) {
            $out[] = DataHelper::get($item, $column);
        }

        return $out;
    }

    public static function first($array, $callback, $default = null)
    {
        if (is_iterable($array)) {
            foreach ($array as $key => $value) {
                if ($callback($value, $key)) {
                    return $value;
                }
            }
        }

        return $default;
    }

    /**
     * Pop value from associative array and delete it in the array
     * @param $arr
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public static function assocPop(&$arr, $key, $default = null)
    {
        if (isset($arr[$key])) {
            $val = $arr[$key];
            unset($arr[$key]);
            return $val;
        }

        return $default;
    }
}
