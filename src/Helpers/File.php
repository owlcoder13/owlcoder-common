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
}
