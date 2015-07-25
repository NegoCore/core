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
 * Class NegoCore_Controller_System
 *
 * Default controller for NegoCore System
 */
class NegoCore_Controller_System extends Controller {

    /**
     * @var bool|array Extra params to add in redirects
     */
    public $query_params = false;

    /**
     * Default NegoCore action
     */
    public function action_default()
    {
        // Is an ajax request
        if ($this->request->is_ajax())
            $this->go_home();

        // Show message
        $this->response->body('<h1>Welcome</h1> This is a default controller for NegoCore, you\'re now ready to develop.<br><br>Have fun!');
    }

    // ----------------------------------------------------------------------

    /**
     * The current request is a backend request?
     *
     * @return bool
     * @throws Kohana_Exception
     */
    public function is_backend()
    {
        return is_string(BACKEND_DIR_NAME) ? URL::match(BACKEND_DIR_NAME, Request::detect_uri()) : false;
    }

    // ----------------------------------------------------------------------

    /**
     * Go to home page, depends of controller type
     *
     * @throws Kohana_Exception
     */
    public function go_home()
    {
        if ($this->is_backend())
        {
            $this->go_backend();
        }
        else
        {
            $this->go(Route::get('default')->uri());
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Go to Backend URL
     *
     * @param array|string $controller
     * @param string $action
     * @param string $id
     */
    public function go_backend($controller = null, $action = null, $id = null)
    {
        $this->go(URL::backend($controller, $action, $id));
    }

    // ----------------------------------------------------------------------

    /**
     * Go to back URL
     */
    public function go_back()
    {
        if (Valid::url($this->request->referrer()))
        {
            $this->go($this->request->referrer());
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Go to specified URL
     *
     * @param string $url
     * @param int $code
     */
    public function go($url = null, $code = 302)
    {
        $route_params = array(
            'controller' => strtolower($this->request->controller())
        );

        if (is_array($url) || $url === null)
        {
            if (is_array($url))
            {
                $route_params = Arr::merge($route_params, $url);
            }

            if ($this->is_backend())
            {
                $url = Route::get('backend')->uri($route_params);
            }
            else
            {
                $url = Route::get('default')->uri($route_params);
            }
        }

        if (is_array($this->query_params))
        {
            $url = preg_replace('/\?.*/', '', $url);
            $url .= URL::query($this->query_params, true);
        }

        $this->redirect($url, $code);
    }

    // ----------------------------------------------------------------------

    /**
     * Establecer un mensaje de salida
     *
     * @param string $message
     * @param string $class
     */
    public function set_message($message, $class = 'success')
    {
        Session::instance()->set('message', array('class' => $class, 'text' => $message));
    }

    // ----------------------------------------------------------------------

    /**
     * Establecer un mensaje de error
     *
     * @param $errors
     */
    public function set_errors($errors)
    {
        Twig::set_global('form_errors', $errors);
    }
}