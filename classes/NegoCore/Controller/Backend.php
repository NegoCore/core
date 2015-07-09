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
     * @var bool Backend controllers required authentication¡
     */
    public $auth_required = true;

    /**
     * Configure Backend Controllers
     */
    public function before()
    {
        parent::before();

        // Navigation init
        Navigation::init(Kohana::$config->load('sitemap')->as_array());

        // ----------------------------------------------------------------------
        // Style
        /*Assets::css('global', 'backend.css');

        // Scripts
        Assets::js('jquery', 'lib/jquery.min.js');
        Assets::js('require', 'lib/require.min.js');
        Assets::js('bootstrap', 'lib/bootstrap.min.js', 'jquery');
        Assets::js('core', 'lib/core.js', array('jquery', 'bootstrap'));

        // WebApp Boot library
        Assets::js('webapp', 'webapp/init.js');*/

        // Title
        Document::title('Panel de control');

        // Configure WebApp init data
        WebApp::set_init_data(array(
            'is_backend' => $this->is_backend(),
            'backend_url' => URL::backend(),
            'base_url' => URL::site()
        ));
    }
}
