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
 * Class NegoCore_Controller_Ajax
 */
class NegoCore_Controller_Ajax extends Controller_Module {

    /**
     * @var bool Auth required by default in Ajax Controllers
     */
    public $auth_required = true;

    /**
     * @var bool Process only with XmlHttpRequest
     */
    protected $validate_xhr = true;

    /**
     * @var mixed Save response data before send to Response
     */
    protected $_response;

    /**
     * Some AJAX validations
     *
     * @throws Kohana_Exception
     */
    public function before()
    {
        parent::before();

        // Only works in XmlHttpRequest
        if ($this->validate_xhr === true && ! $this->request->is_ajax())
        {
            $this->response->status(400);
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Format response content
     */
    public function after()
    {
        parent::after();

        // If response is an array or format requested is JSON, encode it
        if ($this->request->param('format') == 'json' || is_array($this->_response))
        {
            $this->response->headers('Content-Type', 'application/json');
            $this->_response = json_encode($this->_response);
        }

        // Assign to body
        $this->response->body($this->_response);
    }

    // ----------------------------------------------------------------------

    /**
     * Get & Set response from a action in this controller
     *
     * @param mixed $content Content of response
     * @param string $status String status of response
     * @return mixed
     */
    public function response($content = null, $status = null)
    {
        // Get current response
        if ($content === null)
            return $this->_response;

        // Store data in special array
        if (is_string($status))
        {
            $content = array(
                'status' => $status,
                'data' => $content
            );
        }

        // Assign content
        $this->_response = $content;

        // Only for warning statement
        return $this;
    }
}