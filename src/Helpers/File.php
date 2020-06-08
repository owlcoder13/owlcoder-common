<?php

namespace Owlcoder\Common\Helpers;

use Illuminate\Support\Str;

class File
{
    public static function splitFileNameExtension($fileName)
    {
        $parts = explode('.', $fileName);
        $ext = array_pop($parts);
        $fileName = join('', $parts);
        return [$fileName, $ext];
    }

    public static function slugFileName($fileName, $replacer = '-')
    {
        list($file, $ext) = self::splitFileNameExtension($fileName);
        $file = transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0080-\uffff] remove', $file);
        $file = preg_replace('/@/u', 'at', $file);
        $file = preg_replace('/[^a-z0-9-]/u', '', $file);

        return $file . '.' . $ext;
    }

    public static function removeDoubleSlash($path)
    {
        return str_replace('//', '', $path);
    }

    public static function uniqueFileName($directory, $fileName, $slug = true)
    {
        if ($slug) {
            $fileName = self::slugFileName($fileName);
        }

        $fullPath = self::removeDoubleSlash($directory . '/' . $fileName);

        if (file_exists($fullPath)) {

            list($fileName, $ext) = self::splitFileNameExtension($fileName);

            $i = 1;

            do {
                $newFileName = $fileName . '-' . $i++ . '.' . $ext;
                $fullPath = self::removeDoubleSlash($directory . '/' . $newFileName);
            } while (file_exists($newFileName));

            return $newFileName;
        }

        return $fileName;
    }

    /**
     * Transform files input to nice array
     * Took from https://www.php.net/manual/en/reserved.variables.files.php
     * [0] => Array
     * (
     *  [name] => facepalm.jpg
     *  [type] => image/jpeg
     *  [tmp_name] => /tmp/php3zU3t5
     *  [error] => 0
     *  [size] => 31059
     * )
     *
     * [1] => Array
     * (
     *  [name] => facepalm2.jpg
     *  [type] => image/jpeg
     *  [tmp_name] => /tmp/phpJutmOS
     *  [error] => 0
     *  [size] => 78085
     * )
     *
     * [2] => Array
     * (
     *  [name] => facepalm3.jpg
     *  [type] => image/jpeg
     *  [tmp_name] => /tmp/php9bNI8F
     *  [error] => 0
     *  [size] => 61429
     * )
     *
     * @param $files
     * @return array
     */
    public static function TransformFiles($files)
    {
        $output = [];
        foreach ($files as $name => $array) {
            foreach ($array as $field => $value) {
                $pointer = &$output[$name];
                if ( ! is_array($value)) {
                    $pointer[$field] = $value;
                    continue;
                }
                $stack = [&$pointer];
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveArrayIterator($value),
                    \RecursiveIteratorIterator::SELF_FIRST
                );
                foreach ($iterator as $key => $value) {
                    array_splice($stack, $iterator->getDepth() + 1);
                    $pointer = &$stack[count($stack) - 1];
                    $pointer = &$pointer[$key];
                    $stack[] = &$pointer;
                    if ( ! $iterator->hasChildren()) {
                        $pointer[$field] = $value;
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Iterates over directory and exclude . and .. items
     * @param $path
     * @return \Generator
     */
    public static function IteratePath($path)
    {
        if (strrpos($path, '/') !== 0) {
            $path .= '/';
        }

        foreach (scandir($path) as $one) {

            if ($one == '.' || $one == '..') {
                continue;
            }

            yield $path . $one;
        }
    }
}
