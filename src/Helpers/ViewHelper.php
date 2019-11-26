<?php

namespace Owlcoder\Common\Helpers;

use Exception;

/**
 * Help to render template files
 * Class ViewHelper
 * @throws Exception
 * @author Mikhail Pimenov <owlcoder1@gmail.com>
 * @package Owlcoder\Common
 * @return string
 */
class ViewHelper
{
    /**
     * @param $file
     * @param array $variables
     */
    public static function Render($file, $variables = [])
    {
        extract($variables);


        if (file_exists($file)) {
            ob_start();

            require $file;

            return ob_get_clean();
        }

        throw new Exception("View does not exists $file");
    }
}