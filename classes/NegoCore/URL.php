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
 * Class URL
 */
class NegoCore_URL extends Kohana_URL {

    /**
     * Compare a uri with current
     *
     * @param $uri
     * @param string $current
     * @return bool
     */
    public static function match($uri, $current = '')
    {
        $uri = trim($uri, '/');

        if ($current === null && Request::current())
        {
            $current = Request::current()->uri();
        }

        $current = trim($current, '/');

        if ($current == $uri)
        {
            return true;
        }

        if (empty($uri))
        {
            return false;
        }

        if (strpos($current, $uri) !== false)
        {
            return true;
        }

        return false;
    }

    // ----------------------------------------------------------------------

    /**
     * Returns a string with a url string based on arguments
     *
     * @param string $route
     * @param string $controller
     * @param string $action
     * @param mixed $id
     * @return string
     */
    public static function route($route = 'default', $controller = null, $action = null, $id = null)
    {
        if ( ! is_array($controller))
        {
            $controller = array(
                'controller' => $controller,
                'action' => $action,
                'id' => $id
            );
        }

        return URL::site(Route::get($route)->uri($controller));
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
    public static function backend($controller = null, $action = null, $id = null)
    {
        if ( ! is_array($controller))
        {
            $controller = array(
                'controller' => $controller,
                'action' => $action,
                'id' => $id
            );
        }

        return URL::site(Route::get('backend')->uri($controller));
    }
}