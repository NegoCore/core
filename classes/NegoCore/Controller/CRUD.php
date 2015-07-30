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
 * Class NegoCore_Controller_CRUD
 */
class NegoCore_Controller_CRUD extends Controller_Template {

    /**
     * Function for easy create a Object
     *
     * @param string $model Model name, the same in ORM::factory($model)
     * @param array $messages String param is success message.
     */
    public function create($model, $messages = array())
    {
        // Only if Request is POST
        if ($this->request->method() == Request::POST)
        {
            // By default this params can be succes message
            if (is_string($messages))
            {
                $messages = array('success' => $messages);
            }

            // Catch ORM_Validation
            try {

                // Creates object, set values and create.
                $object = ORM::factory($model)
                    ->values($this->request->post())
                    ->create();

                // If object is saved....
                if ($object->saved())
                {
                    // Success message
                    Messages::success(isset($messages['success']) ? $messages['success'] : 'El elemento fue registrado correctamente.');

                    // Redirect to...
                    $this->_redirect_to($object->pk());
                }
            } catch (ORM_Validation_Exception $e) {

                // Error message
                if (isset($messages['error']))
                {
                    Messages::error($messages['error']);
                }

                // Validation messages
                Messages::validation($e);
            }
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Function to validate the object when read.
     *
     * @param ORM $object
     * @param null $message
     */
    public function read(ORM &$object, $message = null)
    {
        // Check if is a valid object
        if ( ! $object->loaded())
        {
            Messages::warning($message !== null ? $message : 'El elemento que intentas leer no existe o fue eliminado.');
            $this->go();
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Function for easy update a ORM object
     *
     * @param ORM $object ORM object to update
     * @param array $messages Array of custom messages
     */
    public function update(ORM $object, array $messages = array())
    {
        // Check if is a valid object
        $this->read($object, isset($messages['warning']) ? $messages['warning'] : 'El elemento que intentas modificar no existe o fue eliminado.');

        // Only if Request is POST
        if ($this->request->method() == Request::POST)
        {
            // Catch ORM_Validation
            try {

                // Set object values and update
                $object
                    ->values($this->request->post())
                    ->update();

                // If object is saved....
                if ($object->saved())
                {
                    // Success message
                    Messages::success(isset($messages['success']) ? $messages['success'] : 'El elemento fue modificado correctamente.');

                    // Redirect to...
                    $this->_redirect_to($object->pk());
                }
            } catch (ORM_Validation_Exception $e) {

                // Error message
                if (isset($messages['error']))
                {
                    Messages::error($messages['error']);
                }

                // Validation messages
                Messages::validation($e);
            }
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Redirect to...
     *
     * @param int $object_id
     */
    protected function _redirect_to($object_id)
    {
        if (method_exists($this, 'action_read'))
        {
            $this->go(array(
                'action' => 'read',
                'id' => $object_id
            ));
        }
        else
        {
            $this->go();
        }
    }
}