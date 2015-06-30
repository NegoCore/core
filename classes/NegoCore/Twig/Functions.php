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
 * Class NegoCore_Twig_Functions
 *
 * This class provide all Twig functions for NegoCore.
 */
class NegoCore_Twig_Functions {

    /**
     * @var array Classes loaded by get_class()
     */
    protected static $_classes = array();

    /**
     * Get a Kohana & NegoCore context class
     *
     * @param string $class Name of class
     * @return mixed
     * @throws Twig_Exception
     */
    public static function get_class($class)
    {
        if ( ! class_exists($class))
        {
            throw new Twig_Exception(
                'The class :class that you try load, does not exist.',
                array(':class' => $class)
            );
        }

        if ( ! isset(self::$_classes[$class]))
        {
            self::$_classes[$class] = new Twig_Class($class);
        }

        return self::$_classes[$class];
    }
}