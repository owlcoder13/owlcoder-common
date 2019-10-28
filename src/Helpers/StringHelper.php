<?php

namespace Owl\Common\Helpers;

class StringHelper
{
    public static function AgeWordFormat($num)
    {
        if ($num == 0) {
            return 'лет';
        }

        $toStr = $num . '';
        $e = mb_substr($toStr, -1);

        $two = mb_substr($toStr, -2);

        if (in_array($two, ['11', '12', '13', '14'])) {
            return 'лет';
        }

        if (in_array($e, ['1'])) {
            return 'год';
        }

        if (in_array($e, ['2', '3', '4'])) {
            return 'года';
        }

        return 'лет';
    }

    public static function FormatPrice($price)
    {
        return number_format($price, 2, '.', ' ');
    }

    public static function startsWith($source, $startsWith)
    {
        return mb_strpos($source, $startsWith) === 0;
    }

    public static function endsWith($text, $endsWith)
    {
        $pos = mb_strrpos($text, $endsWith);
        return $pos === mb_strlen($text) - mb_strlen($endsWith);
    }
}