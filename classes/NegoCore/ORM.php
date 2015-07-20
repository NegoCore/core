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
 * Class NegoCore_ORM
 */
class NegoCore_ORM extends Kohana_ORM {

    /**
     * @var array Created column definition
     */
    protected $_created_column = array(
        'column' => 'created_at',
        'format' => true
    );

    /**
     * @var array Updated column definition
     */
    protected $_updated_column = array(
        'column' => 'updated_at',
        'format' => true
    );
}