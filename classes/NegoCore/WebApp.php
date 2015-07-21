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
     * @var string Name of boot module
     */
    protected static $_boot_module;

    /**
     * @var Config_Group WebApp configuration.
     */
    protected static $_config;

    /**
     * Initialize WebApp & returns a JavaScript var with all required data
     *
     * @return string
     */
    public static function init()
    {
        // Load Assets
        if ($scripts = WebApp::get_config('js'))
        {
            foreach ($scripts as $script)
            {
                call_user_func_array(array('Assets', 'js'), $script);
            }
        }

        // WebApp
        $WebApp = array();

        // Init Data
        $WebApp['data'] = self::get_init_data(false);

        // Boot module
        $WebApp['module'] = self::get_boot_module();

        // RequireJS Config
        $WebApp['requirejs'] = self::_get_requirejs_config();

        // WebApp Inputs
        $output = '<script type="application/javascript">var WebApp = ';
        $output .= json_encode($WebApp);
        $output .= ';</script>';

        return $output;
    }


    // ----------------------------------------------------------------------

    /**
     * Get WebApp configuration
     *
     * @param string $key Config name
     * @return Config_Group|mixed
     * @throws Kohana_Exception
     */
    public static function get_config($key = null)
    {
        if (self::$_config === null)
        {
            self::$_config = Kohana::$config->load('webapp');
        }

        return ($key === null) ? self::$_config : self::$_config->get($key);
    }

    // ----------------------------------------------------------------------

    /**
     * Set init data for WebApp
     *
     * @param string $name Data name
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
            self::$_init_data[$key] = $value;
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
        return $json !== true ? self::$_init_data : json_encode(self::$_init_data);
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
        $boot_module = parse_url(WebApp::get_url('webapp/boot/'.$action, $module));

        self::$_boot_module = trim($boot_module['path'], '/');
    }

    // ----------------------------------------------------------------------

    /**
     * Get boot module name
     *
     * @return string
     */
    public static function get_boot_module()
    {
        return self::$_boot_module;
    }

    // ----------------------------------------------------------------------

    /**
     * Get WebApp URL
     *
     * @param string $src Source name
     * @param string $module Module name
     * @return string
     */
    public static function get_url($src = null, $module = null)
    {
        // Base URL
        $url = WebApp::get_config('url');

        // Only the WebApp URL
        if ($src === null)
            return $url;

        // Trim /
        $url = trim($url, '/').'/';

        // Module or Application
        if ($module !== null)
            $url .= WebApp::get_config('mod_path').'/'.$module;
        else
            $url .= WebApp::get_config('app_path');

        //
        return $url.'/'.$src;
    }

    // ----------------------------------------------------------------------

    protected static function _get_requirejs_config()
    {
        // Main configuration
        $config = WebApp::get_config('requirejs');

        if (empty($config) || ! isset($config['paths']))
        {
            throw new NegoCore_Exception('Configuration for RequireJS is corrupted in WebApp.');
        }

        // Set WebApp Paths
        $config['paths']['modules'] = rtrim(WebApp::get_url('', ''), '/');
        $config['paths']['webapp'] = WebApp::get_url('webapp');
        $config['paths']['bootstrap'] = WebApp::get_url('webapp/bootstrap');
        $config['paths']['core'] = WebApp::get_url('webapp/core/lib');
        $config['paths']['mixin'] = WebApp::get_url('webapp/mixins');

        // WebApp files is loaded every request
        if (Kohana::$environment === Kohana::DEVELOPMENT)
        {
            $config['urlArgs'] = 'v='.time();
        }

        // Return as JSON
        return $config;
    }
}
