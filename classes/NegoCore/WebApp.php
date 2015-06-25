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
 * Class NegoCore_WebApp
 */
class NegoCore_WebApp {

    /**
     * Init Data
     * @var array
     */
    protected static $_init_data = array();

    /**
     * Boot module
     * @var string
     */
    protected static $_boot_module;

    /**
     * Module path
     * @var string
     */
    protected static $_module_path;

    /**
     * Set a data for init module.
     *
     * @param string $name
     * @param mixed $value
     */
    public static function set_init_data($name, $value = null)
    {
        if ( ! is_array($name))
        {
            $name = array($name => $value);
        }

        foreach ($name as $key => $value)
        {
            WebApp::$_init_data[$key] = $value;
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Get init data
     *
     * @param bool $json
     * @return array
     */
    public static function get_init_data($json = true)
    {
        return $json !== true ? WebApp::$_init_data : json_encode(WebApp::$_init_data);
    }

    // ----------------------------------------------------------------------

    /**
     * Set module name
     *
     * @param $module
     * @param string $action
     */
    public static function set_boot_module($module, $action = 'index')
    {
        WebApp::$_boot_module = 'modules/' . $module . '/webapp/boot/' . $action;
    }

    // ----------------------------------------------------------------------

    /**
     * Get module name
     *
     * @return string
     */
    public static function get_boot_module()
    {
        return WebApp::$_boot_module;
    }

    // ----------------------------------------------------------------------

    /**
     * Get module path
     *
     * @return string
     */
    public static function get_module_path()
    {
        return URL::site();
    }
}