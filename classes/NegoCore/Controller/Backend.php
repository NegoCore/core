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
            'backend_url' => URL::backend(),
            'base_url' => URL::site()
        ));
    }
}
