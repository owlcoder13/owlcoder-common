<?php

namespace Owlcoder\Common\Helpers;

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

    /**
     * Простой маппинг, с помощью callback
     *
     * @param $callable
     * @param $arr
     * @return array
     */
    public static function map($callable, $arr)
    {
        $out = [];
        foreach ($arr as $key => $one) {
            $out[] = $callable($one, $key);
        }

        return $out;
    }

    /**
     * Преобразует массив таким образом, что одно из полей элемента в массиве
     * становится ключём нового ассоциативного массива
     *
     * @param $arr
     * @param $field
     * @return array
     */
    public static function mapFieldAsKey($arr, $field)
    {
        $out = [];

        foreach ($arr as $one) {
            $val = DataHelper::get($one, $field);
            $out[$val] = $one;
        }

        return $out;
    }

    /**
     * Фильтрует массив в соответствии с callback функцией
     * Может сохранять ключи, при фильтрации, если требуется
     *
     * @param $arr
     * @param $callback
     * @param false $keepKeys
     * @return array
     */
    public static function filter($arr, $callback, $keepKeys = false)
    {
        $out = [];

        foreach ($arr as $key => $one) {

            if ($callback($one, $key)) {
                if ($keepKeys) {
                    $out[$key] = $one;
                } else {
                    $out[] = $one;
                }
            }
        }

        return $out;
    }
}
