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
     *         'my_method' => array('MyClass', 'my_method'),
     *     ),
     */
    'functions' => array(
        'get_messages' => array('Messages', 'get'),
        'get_form_error' => array('Messages', 'form_error')
    ),
    'filters' => array(
        'without' => array('Twig_Filters', 'without')
    ),
    'tests' => array(),
);