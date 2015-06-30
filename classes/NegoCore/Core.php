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