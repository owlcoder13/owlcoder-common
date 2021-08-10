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
     * $callback принимает в себя параметры $item, $key (ключ идёт вторым параметром)
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

    /**
     * Ищет первый элемент в массиве. также как и filter
     *
     * @param $arr
     * @param $callback
     * @param false $keepKeys
     * @return array
     */
    public static function find($arr, $callback, $returnKey = false)
    {
        $out = [];

        foreach ($arr as $key => $one) {

            if ($callback($one, $key)) {
                if ($returnKey) {
                    return [$one, $key];
                } else {
                    return $one;
                }
            }
        }

        return $out;
    }

    /**
     * Преобразует модели по заданному конфигу в массив
     *
     * @param $model
     * @param $options
     * @return array
     * @throws \Exception
     */
    public static function toArray($model, $options)
    {
        $isArray = is_array($model);

        if ($isArray) {
            $o = static::map(function ($item) use ($options) {
                return static::toArray($item, $options);
            }, $model);
            return $o;

        }

        $class = get_class($model);

        $out = [];

        if (isset($options[$class])) {

            foreach ($options[$class] as $key => $one) {

                if (is_string($one)) {
                    $a = $model->{$one};

                    if (is_object($a)) {
                        $out[$one] = static::toArray($a, $options);
                    } else if (is_array($a)) {
                        $out[$one] = static::toArray($a, $options);
                    } else {
                        $out[$one] = $a;
                    }
                } else if (is_callable($one)) {
                    $out[$key] = $one($model);
                }
            }
        } else {
            throw new \Exception("dont know how to translate to array: " . $class);
        }

        return $out;
    }

    /**
     * Возвращает key => value массив.
     * $callback должен возвращать массив из двух элементов - ключ, значение
     *
     * @param $arr
     * @param $callback
     * @return array
     */
    public static function mapKeyValue($arr, $callback)
    {
        $out = [];

        foreach ($arr as $k => $item) {
            list($key, $value) = $callback($item, $k);
            $out[$key] = $value;
        }

        return $out;
    }
}
