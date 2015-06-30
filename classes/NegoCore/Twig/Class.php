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
 * Class NegoCore_Twig_Class
 *
 * When get_class() function load a class, an instance of this is created.
 */
class NegoCore_Twig_Class {

    /**
     * @var string The class name
     */
    protected $_class;

    /**
     * Constructor
     *
     * @param string $class Class name
     */
    public function __construct($class)
    {
        $this->_class = $class;
    }

    // ----------------------------------------------------------------------

    /**
     * Magic method to get class method.
     *
     * @param string $method Method name
     * @param array $arguments Array of method arguments
     * @return mixed|null
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->_class, $method))
        {
            return call_user_func_array(array($this->_class, $method), $arguments);
        }

        return null;
    }

    // ----------------------------------------------------------------------

    /**
     * Class to String
     *
     * @return string
     */
    public function __toString()
    {
        return __(
            'Twig_Exception: The class :class could not be converted to string.',
            array(':class' => $this->_class)
        );
    }
}