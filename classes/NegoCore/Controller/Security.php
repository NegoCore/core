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
 *
 * Class Controller_Security
 */
class NegoCore_Controller_Security extends Controller_System {

    /**
     * Controller requires authentication
     *
     * @var bool
     */
    public $auth_required = false;

    /**
     * The controller is private but contents public actions.
     *
     * @var array
     */
    public $public_actions = array();

    /**
     * The user can access to allowed actions
     *
     * @var array
     */
    public $allowed_actions = array();

    /**
     * Before the controller execute an action, check security access.
     * @throws HTTP_Exception
     */
    public function before()
    {
        parent::before();

        // Check public actions
        if ($this->auth_required === true && ! Auth::instance()->logged_in() && ! in_array($this->request->action(), $this->public_actions))
        {
            $this->_deny_access();
        }

        // Check allowed actions
        if ($this->auth_required === true && ! in_array($this->request->action(), $this->allowed_actions) && ! ACL::check($this->request))
        {
            $this->_deny_access();
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Send response with error code.
     *
     * @param string $message
     * @throws HTTP_Exception
     */
    protected function _deny_access($message = null)
    {
        if (Auth::instance()->logged_in() || $this->request->is_ajax())
        {
            if ($message === null)
            {
                $message = 'No tienes permisos para acceder a esta página';
            }

            throw HTTP_Exception::factory(403, $message);
        }
        else
        {
            throw HTTP_Exception::factory(401);
        }
    }
}