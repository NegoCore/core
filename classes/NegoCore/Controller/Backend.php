<?php defined('NEGOCORE') or die('No direct script access.');

/**
 * NegoCore
 *
 * @author      Iván Molina Pavana <imp@negocad.mx>
 * @copyright   Copyright (c) 2015, NegoCAD <info@negocad.mx>
 * @version     1.0.0
 */

// --------------------------------------------------------------------------------

class NegoCore_Controller_Backend extends Controller_Template {

    /**
     * Configure Backend Controllers
     */
    public function before()
    {
        parent::before();

        // ----------------------------------------------------------------------

        // Navigation
        Navigation::init(Kohana::$config->load('sitemap')->as_array());

        // Set to view
        $this->view->set_global('navigation', Navigation::get());
        $this->view->set_global('page', Navigation::$current);

        // ----------------------------------------------------------------------
        // Style
        Assets::css('global', 'backend.css');

        // Scripts
        Assets::js('jquery', 'lib/jquery.min.js');
        Assets::js('require', 'lib/require.min.js');
        Assets::js('bootstrap', 'lib/bootstrap.min.js', 'jquery');
        Assets::js('core', 'lib/core.js', array('jquery', 'bootstrap'));

        // WebApp Boot library
        Assets::js('webapp', 'webapp/init.js');

        // Title
        Document::title('Panel de control');

        // Configure WebApp init data
        WebApp::set_init_data(array(
            'is_backend' => $this->is_backend(),
            'backend_url' => '/' . BACKEND_DIR_NAME . '/',
            'base_url' => URL::site()
        ));
    }
}