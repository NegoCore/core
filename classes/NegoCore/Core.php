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
 * Class NegoCore_Core
 */
class NegoCore_Core {

    /**
     * Auto load Backend & Frontend classes
     *
     * @param string $class
     * @return bool
     */
    public static function auto_load($class)
    {
        if (strpos($class, '_Controller_') !== false)
        {
            list($module, $dir, $file) = explode('_', $class);

            // Maybe MODPATH constant works for now, I do not know in future... Check it!
            $filename = MODPATH.strtolower($module).DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file.EXT;

            if (is_file($filename))
            {
                require $filename;

                return true;
            }
        }

        return false;
    }

    // ----------------------------------------------------------------------

    /**
     * Call a class from Kohana or NegoCore since Twig view.
     *
     * @param $class
     * @param $arguments
     * @return mixed|null
     */
    public function __call($class, $arguments)
    {
        list($method, $args) = $arguments;

        if (class_exists($class) && method_exists($class, $method))
        {
            return call_user_func_array(array($class, $method), $args);
        }

        return null;
    }

    // ----------------------------------------------------------------------

    /**
     * Return a config group
     *
     * @param $group
     * @return Kohana_Config_Group
     * @throws Kohana_Exception
     */
    public function config($group)
    {
        return Kohana::$config->load($group);
    }

    // ----------------------------------------------------------------------

    /**
     * Returns a string with a url string based on arguments
     *
     * @param string $controller
     * @param string $action
     * @param mixed $id
     * @param string $route
     * @return string
     */
    public function get_url($controller = null, $action = null, $id = null, $route = 'default')
    {
        return URL::route($route, $controller, $action, $id);
    }

    // ----------------------------------------------------------------------

    /**
     * Returns a string with a backend url string based on arguments
     *
     * @param string $controller
     * @param string $action
     * @param mixed $id
     * @return string
     */
    public function backend_url($controller = null, $action = null, $id = null)
    {
        return URL::backend($controller, $action, $id);
    }

    // ----------------------------------------------------------------------

    /**
     * Returns the filename of view.
     *
     * @param string $module Name of module
     * @param string $view Name with directory of view
     * @return string
     */
    public function module_view($module, $view)
    {
        return MODPATH.$module.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$view.'.twig';
    }

    // ----------------------------------------------------------------------

    /**
     * @param array $options
     * @param string $select_text
     * @return array
     */
    public function forms_get_options(array $options = array(), $select_text = 'Seleccionar...')
    {
        if ( ! isset($options[0]))
        {
            $_options = array(null => $select_text);

            foreach ($options as $key => $value)
            {
                $_options[$key] = $value;
            }

            $options = $_options;
        }

        return $options;
    }
}