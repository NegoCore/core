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
 * Class NegoCore_Twig_Functions
 *
 * This class provide all Twig functions for NegoCore.
 */
class NegoCore_Twig_Functions {

    /**
     * @var array Classes loaded by get_class()
     */
    protected static $_classes = array();

    /**
     * @var Twig_Config
     */
    protected static $_module_config;

    /**
     * Get a Kohana & NegoCore context class
     *
     * @param string $class Name of class
     * @return mixed
     * @throws Twig_Exception
     */
    public static function get_class($class)
    {
        if ( ! class_exists($class))
        {
            throw new Twig_Exception(
                'The class :class that you try load, does not exist.',
                array(':class' => $class)
            );
        }

        if ( ! isset(self::$_classes[$class]))
        {
            self::$_classes[$class] = new Twig_Class($class);
        }

        return self::$_classes[$class];
    }

    // ----------------------------------------------------------------------

    /**
     * Get configuration
     *
     * @param string $group Group name
     * @param string $module Module name
     * @return Kohana_Config_Group|mixed
     * @throws Twig_Exception
     */
    public static function get_config($group, $module = null)
    {
        // Kohana config
        if ($module === null)
            return Kohana::$config->load($group);

        // Module config
        if (self::$_module_config === null)
        {
            self::$_module_config = new Twig_Config();
        }

        return self::$_module_config->load($group, $module);
    }

    // ----------------------------------------------------------------------

    /**
     * Get URL
     *
     * @param string $controller Controller name or route name
     * @param string|array $action Action name or array to pass to route
     * @param mixed $id
     * @return string
     */
    public static function get_url($controller = '', $action = null, $id = null)
    {
        if ($action === null && $id === null)
            return URL::site($controller);

        if (is_array($action))
            return URL::route($controller, $action);

        return URL::route('default', $controller, $action, $id);
    }

    // ----------------------------------------------------------------------

    /**
     * Get Backend URL
     *
     * @param string $controller Controller name
     * @param string $action Action name
     * @param mixed $id
     * @return string
     */
    public static function get_backend_url($controller = '', $action = null, $id = null)
    {
        return self::get_url('backend', array(
            'controller' => $controller,
            'action' => $action,
            'id' => $id
        ));
    }
}