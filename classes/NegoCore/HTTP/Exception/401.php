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
 * Class NegoCore_HTTP_Exception_401
 */
class NegoCore_HTTP_Exception_401 extends Kohana_HTTP_Exception_401 {

    /**
     * Generate a Response for the 401 Exception
     *
     * The user should be redirect to a login page.
     *
     * @return Response
     * @throws Kohana_Exception
     */
    public function get_response()
    {
        Flash::set('redirect', Request::current()->uri());

        return Response::factory()
            ->status(401)
            ->headers('Location', URL::backend('auth', 'login'));
    }
}