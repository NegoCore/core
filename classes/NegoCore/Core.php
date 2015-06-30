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
 * Class NegoCore_Core
 */
class NegoCore_Core {

    /**
     * @param array $options
     * @param string $select_text
     * @return array
     */
    public function forms_get_options(array $options = array(), $select_text = 'Seleccionar...')
    {
        if ( ! isset($options[0]))
        {
            $_options = array(null => $select_text);

            foreach ($options as $key => $value)
            {
                $_options[$key] = $value;
            }

            $options = $_options;
        }

        return $options;
    }
}