<?php

namespace Owlcoder\Common\Helpers;

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

    public static function transliterate($text)
    {
        $converter = array(
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
            'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
            'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ф' => 'F', 'Х' => 'X', 'Ц' => 'Cz', 'Ч' => 'Ch',
            'Ш' => 'Sh', 'Щ' => 'Shh', 'Ъ' => '``', 'Ы' => 'Y`', 'Ь' => '`',
            'Э' => 'E`', 'Ю' => 'Yu', 'Я' => 'Ya',

            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'x', 'ц' => 'cz', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shh', 'ъ' => '``', 'ы' => 'y`', 'ь' => '`',
            'э' => 'e`', 'ю' => 'yu', 'я' => 'ya'
        );

        $text = strtr($text, $converter);

        return $text;
    }

    public static function onlyNumbers($text)
    {
        return preg_replace('/[^0-9]/', '', $text);
    }

    public static function formatPhone($phone)
    {
        $phone = self::onlyNumbers($phone);

        if (isset($phone[0]) && strlen($phone) != 11 and ($phone[0] != '7' or $phone[0] != '8')) {
            return FALSE;
        }

        $phone_number['dialcode'] = substr($phone, 0, 1);
        $phone_number['code'] = substr($phone, 1, 3);
        $phone_number['phone'] = substr($phone, -7);
        $phone_number['phone_arr'][] = substr($phone_number['phone'], 0, 3);
        $phone_number['phone_arr'][] = substr($phone_number['phone'], 3, 2);
        $phone_number['phone_arr'][] = substr($phone_number['phone'], 5, 2);

        if ($phone_number['dialcode'] == 8) {
            $phone_number['dialcode'] = 7;
        }

        $format_phone = '+' . $phone_number['dialcode'] . ' (' . $phone_number['code'] . ') ' . implode('-', $phone_number['phone_arr']);

        return $format_phone;
    }
}
