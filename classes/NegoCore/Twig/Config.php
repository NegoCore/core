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
 * Class NegoCore_Twig_Config
 */
class NegoCore_Twig_Config {

    /**
     * @var array Config module groups
     */
    protected $_config_groups = array();

    /**
     * Load modules config
     *
     * @param $group
     * @param $module
     * @return mixed
     * @throws Kohana_Exception
     */
    public function load($group, $module)
    {
        if (empty($group) || empty($module))
        {
            throw new Twig_Exception("Need to specify a config group and module name");
        }

        if ( ! is_string($group) || ! is_string($module))
        {
            throw new Twig_Exception("Config group and module name must be a string");
        }

        if (strpos($group, '.') !== FALSE)
        {
            // Split the config group and path
            list($group, $path) = explode('.', $group, 2);
        }

        if (isset($this->_config_groups[$group]))
        {
            if (isset($path))
            {
                return Arr::path($this->_config_groups[$group], $path, NULL, '.');
            }

            return $this->_config_groups[$group];
        }

        $config = array();

        $file = $this->_get_config_file($group, $module);

        if (is_file($file))
        {
            $config = Arr::merge($config, Kohana::load($file));
        }

        $this->_config_groups[$group] = new Config_Group(Kohana::$config, $group, $config);

        if (isset($path))
        {
            return Arr::path($config, $path, NULL, '.');
        }

        return $this->_config_groups[$group];
    }

    // ----------------------------------------------------------------------

    /**
     * Get path of config group in a module.
     *
     * @param string $group File name
     * @param string $module Module name
     * @return string
     */
    protected function _get_config_file($group, $module)
    {
        return MODPATH.$module.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.$group.EXT;
    }
}