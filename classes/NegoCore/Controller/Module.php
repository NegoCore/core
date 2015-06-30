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
        $this->_module_name = trim(str_replace(MODPATH, '', $module_path), '/');
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
        return Twig_Functions::get_config($group, $this->_module_name);
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