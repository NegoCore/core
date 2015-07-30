<?php defined('NEGOCORE') or die('No direct script access.');

/**
 * NegoCore
 *
 * @author      Iván Molina Pavana <imp@negocad.mx>
 * @copyright   Copyright (c) 2015, NegoCAD <info@negocad.mx>
 * @version     1.0.0
 */

// --------------------------------------------------------------------------------

class NegoCore_Controller_Backend extends Controller_CRUD {

    /**
     * @var bool Backend controllers required authentication¡
     */
    public $auth_required = true;

    /**
     * Configure Backend Controllers
     */
    public function before()
    {
        parent::before();

        // Configure Backend
        require_once APPPATH.'config'.DIRECTORY_SEPARATOR.'backend.php';

        // Navigation init
        Navigation::init(Kohana::$config->load('sitemap')->as_array());

        // Configure WebApp init data
        WebApp::set_init_data(array(
            'is_backend' => true,
            'assets_url' => Assets::get_url(),
            'backend_url' => URL::backend(),
            'base_url' => URL::site()
        ));
    }

    /**
     * Overwrite default read() function to add page_object_id to Navigation
     *
     * @param ORM $object
     * @param null $message
     */
    public function read(ORM &$object, $message = null)
    {
        parent::read($object, $message);

        // Set Object ID for navigation buttons
        $this->view->set('page_object_id', $object->pk());
    }
}
