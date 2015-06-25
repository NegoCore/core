<?php defined('NEGOCORE') or die('No direct script access.');

/**
 * NegoCore
 *
 * @author      Iván Molina Pavana <imp@negocad.mx>
 * @copyright   Copyright (c) 2015, NegoCAD <info@negocad.mx>
 * @version     1.0.0
 */

// --------------------------------------------------------------------------------

/**
 * Class NegoCore_Twig_Filters
 *
 * Filters for Twig Engine
 */
class NegoCore_Twig_Filters {

    /**
     * Delete array element
     *
     * @param array $array
     * @param string $without
     * @return mixed
     */
    public static function without(array $array, $without)
    {
        if ( ! is_array($without))
        {
            $without = array($without);
        }

        foreach ($without as $key)
        {
            unset($array[$key]);
        }

        return $array;
    }
}