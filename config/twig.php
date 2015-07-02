<?php defined('NEGOCORE') or die('No direct script access.');

/**
 * Skeleton Module
 *
 * @author      Iván Molina Pavana <imp@negocad.mx>
 * @copyright   Copyright (c) 2015, NegoCAD <info@negocad.mx>
 * @version     1.0.0
 */

// --------------------------------------------------------------------------------

/**
 * Twig Module Configs
 *
 * @filesource
 */
return array(

    /**
     * Twig Environment options
     *
     * http://twig.sensiolabs.org/doc/api.html#environment-options
     */
    'environment' => array(
        'autoescape'          => false,
    ),

    /**
     * Custom functions, filters and tests
     *
     *     'functions' => array(
     *         array('my_method', array('MyClass', 'my_method'), array('pre_scape' => false)),
     *     ),
     */
    'functions' => array(
        // Assets
        array('get_css', array('Assets', 'all_css'), array('is_safe' => array('html'))),
        array('get_js', array('Assets', 'all_js'), array('is_safe' => array('html'))),
        array('image', array('Assets', 'image'), array('is_safe' => array('html'))),
        // Document
        array('get_title', array('Document', 'get_title'), array('is_safe' => array('html'))),
        array('get_meta_tags', array('Document', 'all_meta'), array('is_safe' => array('html'))),
        // Messages
        array('get_messages', array('Messages', 'get')),
        array('get_form_error', array('Messages', 'form_error')),
        // Navigation
        array('get_navigation', array('Navigation', 'get')),
        array('get_current_page', array('Navigation', 'get_current_page')),
        // Twig Functions
        array('get_class', array('Twig_Functions', 'get_class')),
        array('get_config', array('Twig_Functions', 'get_config')),
        array('get_url', array('Twig_Functions', 'get_url')),
        array('get_backend_url', array('Twig_Functions', 'get_backend_url')),
        array('get_module_view', array('Twig_Functions', 'get_module_view')),
        array('get_fs_options', array('Twig_Functions', 'get_fs_options'))
    ),
    'filters' => array(
        array('without', array('Twig_Filters', 'without'))
    ),
    'tests' => array(),
);