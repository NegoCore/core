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
 * Class NegoCore_Kohana
 *
 * Overwrite Kohana Core
 */
class NegoCore_Kohana extends Kohana_Core {

    /**
     * @var array Classes will be loaded from...
     */
    protected static $_paths = array(APPPATH, NC_SYSPATH, KH_SYSPATH);

    /**
     * Set enabled modules for NegoCore platform.
     *
     * @param array $modules
     * @return array
     * @throws Kohana_Exception
     */
    public static function modules(array $modules = null)
    {
        if ($modules === NULL)
        {
            // Not changing modules, just return the current set
            return Kohana::$_modules;
        }

        // Compose modules array
        $_modules = $modules;
        $modules = array();
        foreach ($_modules as $provider => $module_list)
        {
            $modules += $module_list;
        }

        // Start a new list of include paths, APPPATH first
        $paths = array(APPPATH);

        foreach ($modules as $name => $path)
        {
            if (is_dir($path))
            {
                // Add the module to include paths
                $paths[] = $modules[$name] = realpath($path).DIRECTORY_SEPARATOR;
            }
            else
            {
                // This module is invalid, remove it
                throw new NegoCore_Exception('Attempted to load an invalid or missing module \':module\' at \':path\'', array(
                    ':module' => $name,
                    ':path'   => Debug::path($path),
                ));
            }
        }

        // Finish the include paths by adding system paths
        $paths[] = NC_SYSPATH;
        $paths[] = KH_SYSPATH;

        // Set the new include paths
        Kohana::$_paths = $paths;

        // Set the current module list
        Kohana::$_modules = $modules;

        foreach (Kohana::$_modules as $path)
        {
            $init = $path.'init'.EXT;

            if (is_file($init))
            {
                // Include the module initialization file once
                require_once $init;
            }
        }

        return Kohana::$_modules;
    }
}