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
 * Class NegoCore_Controller_Template
 */
class NegoCore_Controller_Template extends Controller_Module {

    /**
     * Auto render template
     * @var bool
     */
    public $auto_render = true;

    /**
     * Twig view handler
     * @var Twig
     */
    public $view;

    /**
     * Setup View with Twig library
     */
    public function before()
    {
        parent::before();

        if ($this->auto_render === true)
        {
            // The view is handle by Twig
            $this->view = new Twig();
        }

        // Common meta tags
        Document::meta('charset', array('charset' => 'utf-8'));
        Document::meta('viewport', array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, minimum-scale=1'));
    }

    // ----------------------------------------------------------------------

    /**
     * Generate HTML code from page
     */
    public function after()
    {
        parent::after();

        if ($this->auto_render)
        {
            // Add NegoCore Helper
            $this->view->set_global('Core', new NegoCore());

            // Generate default filename
            if ($this->view->get_filename() === null)
            {
                $this->view->set_filename($this->find_view());
            }

            // Messages & Errors
            $this->view->set('message', Session::instance()->get_once('message'));
            $this->view->set('errors', Session::instance()->get_once('errors'));

            // Set body
            $this->response->body($this->view);
        }
    }

    // ----------------------------------------------------------------------

    /**
     * @return string
     * @throws Kohana_Exception
     * @throws Twig_Error_Loader
     */
    public function find_view()
    {
        // Directory
        $directory = strtolower($this->request->directory());
        $directory = empty($directory) ? '' : $directory.DIRECTORY_SEPARATOR;

        // Controller
        $controller = strtolower($this->request->controller());
        $controller = ($controller == $this->_module_name) ? '' : $controller.DIRECTORY_SEPARATOR; // Si el controlador es el principal cargar las vistas desde el ROOT

        // Action
        $action = strtolower($this->request->action());

        $ext = Kohana::$config->load('twig.loader.extension');
        $file = $this->find_file('views', $directory.$controller.$action, $ext);

        if ( ! $file)
        {
            throw new Twig_Error_Loader(
                __('The requested view ":filename" could not be found on :module module.', array(
                    ':filename' => $directory.$controller.$action.'.'.$ext,
                    ':module' => $this->_module_name
                ))
            );
        }

        return $file;
    }
}