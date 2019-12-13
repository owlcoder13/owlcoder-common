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
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return $default;
    }
}
