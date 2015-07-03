<?php defined('NEGOCORE') or die('No direct script access.');

/**
 * NegoCore
 *
 * @author      Iván Molina Pavana <imp@negocad.mx>
 * @copyright   Copyright (c) 2015, NegoCAD <info@negocad.mx>
 * @version     1.0.0
 */

// --------------------------------------------------------------------------------

class NegoCore_Flash {

    /**
     * Get a flash variable.
     *
     * @param string $var
     * @param mixed $default
     * @return mixed
     */
    public static function get($var, $default = null)
    {
        return Session::instance()->get_once('flash_'.$var, $default);
    }

    /**
     * Add a flash variable.
     *
     * @param string $var
     * @param mixed $value
     */
    public static function set($var, $value)
    {
        Session::instance()->set('flash_'.$var, $value);
    }

    /**
     * Clear a flash variable.
     *
     * @param string $var
     */
    public static function clear($var)
    {
        Session::instance()->delete('flash_'.$var);
    }
}