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
 * Class Controller_Module
 *
 * Module controller
 */
class NegoCore_Controller_Module extends Controller_Security {

    /**
     * @var string Module directory name
     */
    protected $_module_name;

    /**
     * @var string Module path
     */
    protected $_module_path;

    /**
     * @var array Module config groups
     */
    protected $_module_config_groups = array();

    /**
     * Before get module's directory
     */
    public function before()
    {
        parent::before();

        // Get module's directory
        $class = new ReflectionClass(get_called_class());
        $module_path = explode('classes/', $class->getFileName());
        $module_path = $module_path[0];

        // Assign module path
        $this->_module_path = $module_path;

        // Module directory name
        $this->_module_name = str_replace(MODPATH, '', $module_path);
    }

    // ----------------------------------------------------------------------

    /**
     * Get module path
     *
     * @return string
     */
    public function get_module_path()
    {
        return $this->_module_path;
    }

    // ----------------------------------------------------------------------

    /**
     * Get config files from module's directory
     *
     * @param string $group
     * @return mixed|Kohana_Config_Group
     * @throws Kohana_Exception
     */
    public function config($group)
    {
        if (empty($group))
        {
            throw new Kohana_Exception("Need to specify a config group");
        }

        if ( ! is_string($group))
        {
            throw new Kohana_Exception("Config group must be a string");
        }

        if (strpos($group, '.') !== FALSE)
        {
            // Split the config group and path
            list($group, $path) = explode('.', $group, 2);
        }

        if (isset($this->_module_config_groups[$group]))
        {
            if (isset($path))
            {
                return Arr::path($this->_module_config_groups[$group], $path, NULL, '.');
            }

            return $this->_module_config_groups[$group];
        }

        $config = array();

        if ($file = $this->find_file('config', $group))
        {
            // Merge each file to the configuration array
            $config = Arr::merge($config, Kohana::load($file));
        }

        $this->_module_config_groups[$group] = new Config_Group(Kohana::$config, $group, $config);

        if (isset($path))
        {
            return Arr::path($config, $path, NULL, '.');
        }

        return $this->_module_config_groups[$group];
    }

    // ----------------------------------------------------------------------

    /**
     * Find file into module directory
     *
     * @param string $dir
     * @param string $file
     * @param string $ext
     * @return string
     * @throws Twig_Error_Loader
     */
    public function find_file($dir, $file, $ext = null)
    {
        if ($ext === NULL)
        {
            // Use the default extension
            $ext = EXT;
        }
        elseif ($ext)
        {
            // Prefix the extension with a period
            $ext = ".{$ext}";
        }
        else
        {
            // Use no extension
            $ext = '';
        }

        // Create a partial path of the filename
        $filename = $dir.DIRECTORY_SEPARATOR.$file.$ext;

        $found = false;
        if (is_file($this->_module_path.$filename))
        {
            $found = $this->_module_path.$filename;
        }

        return $found;
    }
}