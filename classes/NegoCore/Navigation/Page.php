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
 * Class NegoCore_Navigation_Page
 */
class NegoCore_Navigation_Page extends Navigation_Abstract {

    /**
     * Factory object
     *
     * @param array $data
     * @return Navigation_Page
     */
    public static function factory(array $data = array())
    {
        return new Navigation_Page($data);
    }

    // ----------------------------------------------------------------------

    /**
     * Extra Magic method
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function __set($name, $value)
    {
        parent::__set($name, $value);

        if ($this->_section !== null)
        {
            $this->_section->update();
        }

        return $this;
    }

    // ----------------------------------------------------------------------

    /**
     * Set active status
     *
     * @param bool $status
     * @return $this
     */
    public function set_active($status = true)
    {
        parent::set_active($status);
        $this->_section->set_active($status);

        return $this;
    }
}