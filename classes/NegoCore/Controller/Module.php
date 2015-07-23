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
 * Class NegoCore_Controller_Module
 */
class NegoCore_Controller_Module extends Controller_Security {

    /**
     * @var string Module directory name
     */
    protected $_module_name;

    /**
     * @var bool Auto-load WebApp module
     */
    public $auto_load_webapp = true;

    /**
     * Before get module's directory
     */
    public function before()
    {
        parent::before();

        // Get module's directory
        $class = new ReflectionClass(get_called_class());
        $module_path = explode(DIRECTORY_SEPARATOR.'classes', $class->getFileName());
        $module_path = explode(DIRECTORY_SEPARATOR, $module_path[0]);

        // Module directory name
        $this->_module_name = end($module_path);
    }

    // ----------------------------------------------------------------------

    /**
     * After, auto-load WebApp module
     */
    public function after()
    {
        parent::after();

        if ($this->auto_load_webapp && WebApp::get_boot_module() === null)
        {
            WebApp::set_boot_module($this->_module_name, $this->request->action());
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Set WebApp boot module
     *
     * @param string $action FlightJS module name.
     * @param string|bool $module Module name, if is FALSE then load from 'webapp' directory
     */
    public function set_webapp_module($action, $module = null)
    {
        WebApp::set_boot_module($module === null ? $this->_module_name : ($module === false ? null : $module), $action);
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

        // Create path of the filename
        $filename = Kohana::get_module_path($this->_module_name);
        $filename .= $dir.DIRECTORY_SEPARATOR.$file.$ext;

        $found = false;
        if (is_file($filename))
        {
            $found = $filename;
        }

        return $found;
    }

    // ----------------------------------------------------------------------

    /**
     * Get current controller view path
     *
     * @param string $view
     * @return string
     */
    public function get_view($view)
    {
        return Twig_Functions::get_module_view($this->_module_name, ($this->is_backend() ? 'backend/' : '').$view);
    }
}