<?php

namespace Owlcoder\Common\Helpers;

class Html
{
    public static $selfTag = ['img', 'meta', 'input', 'link'];

    public static function escape($value, $doubleQuotes = true)
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function tag($tagName, $content, $attributes = [])
    {
        $closeTag = in_array(strtolower($tagName), static::$selfTag);

        $html = "<$tagName" . static::buildAttributes($attributes) . ($closeTag ? '/>' : '>');

        if ( ! $closeTag) {
            $html .= "{$content}</{$tagName}>";
        }

        return $html;
    }

    public static function img($src, $attributes = [])
    {
        return self::tag('img', '', array_merge($attributes, ['src' => $src]));
    }

    public static function buildAttributes($attributes)
    {
        if (count($attributes) == 0) {
            return '';
        }

        $out = [];

        foreach ($attributes as $key => $value) {
            if ($value === null) {
                continue;
            }

            $out[] = $key . '="' . static::escape($value) . '"';
        }

        return ' ' . join(' ', $out);
    }

    public static function select($name = '', $value = null, $options = [], $htmlOptions = [])
    {
        $renderOptions = [];

        foreach ($options as $key => $one) {
            $selected = $value == $key ? true : null;
            $renderOptions[] = self::tag('option', $one, [
                'value' => $key,
                'selected' => $selected
            ]);
        }

        return self::tag('select', join('', $renderOptions), array_merge(['name' => $name], $htmlOptions));
    }

    public static function textInput($name = '', $value = null, $htmlOptions = [])
    {
        return self::tag('input', '', array_merge([
            'type' => 'text',
            'name' => $name,
            'value' => $value,
        ], $htmlOptions));
    }

    public static function link($name, $url = '#', $options = [])
    {
        return Html::tag('a', $name, array_merge(['href' => $url], $options));
    }
}
