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
 * Class NegoCore_Table
 *
 * @package Helper
 */
class NegoCore_Table {

    /**
     * @var array Config actions
     */
    protected static $_config_actions = array(
        'default' => array(
            'class' => 'btn-default',
            'icon' => 'fa fa-exclamation-triangle'
        ),
        'read' => array(
            'action' => 'read',
            'class' => 'btn-info',
            'icon' => 'fa fa-eye'
        ),
        'update' => array(
            'action' => 'update',
            'class' => 'btn-primary',
            'icon' => 'fa fa-pencil'
        ),
        'delete' => array(
            'action' => 'delete',
            'class' => 'btn-danger',
            'icon' => 'fa fa-trash-o'
        ),
    );

    /**
     * Render a sortable <th> tag based on current request
     *
     * @param string $column
     * @param string $name
     * @param string|array $attributes
     * @return string
     */
    public static function th($column, $name, $attributes = null)
    {
        static $_query;

        // Only get one per request
        if ($_query === null)
        {
            $_query = Request::current()->query();

            // Default
            $_query['sort'] = isset($_query['sort']) ? strtolower($_query['sort']) : 'id';
            $_query['order'] = isset($_query['order']) ? strtolower($_query['order']) : 'desc';
        }

        // Attributes
        if ( ! is_array($attributes))
        {
            $attributes = array('class' => $attributes);
        }

        // Init
        $class = 'sorting';
        $order = 'asc';

        // This column is selected
        if ($column == $_query['sort'])
        {
            $class .= '_'.$_query['order'];
            $order = $_query['order'] == 'asc' ? 'desc' : 'asc';
        }

        // Add class to element
        $attributes['class'] = trim($class.' '.$attributes['class'], ' ');

        // Build URL query
        $url = URL::query(array(
            'sort' => $column,
            'order' => $order
        ));

        // Return HTML
        return strtr('<th:attrs><a href=":url">:name</a></th>', array(
            ':attrs' => HTML::attributes($attributes),
            ':url' => $url,
            ':name' => $name
        ));
    }

    // ----------------------------------------------------------------------

    /**
     * Render <th> for actions, this calculate width of column
     *
     * @param int $size Number of actions
     * @return string
     */
    public static function th_actions($size = 3)
    {
        $width = (25 * $size) + 50;

        return '<th class="column-actions" style="width: '.$width.'px"></th>';
    }

    // ----------------------------------------------------------------------

    /**
     * Generate table actions based on second argument
     *
     * @param int $id ID of table object
     * @param array $actions List of actions
     * @return string
     */
    public static function actions($id, array $actions)
    {
        $html = '';
        foreach ($actions as $action)
        {
            // Get action config
            $config = isset(self::$_config_actions[$action]) ? self::$_config_actions[$action] : self::$_config_actions['view'];

            // Generate URL
            $uri = Request::current()->route()->uri(array(
                'controller' => strtolower(Request::current()->controller()),
                'action' => $config['action'],
                'id' => $id
            ));

            // Render action
            $html .= Table::action($uri, $config);
        }

        return $html;
    }

    // ----------------------------------------------------------------------

    /**
     * Render a table action
     *
     * @param string $uri URI of action
     * @param array $config Array of action config
     * @return string
     */
    public static function action($uri, array $config = array())
    {
        // Get config & overwrite
        $config = $config + self::$_config_actions['default'];

        // Set attributes
        $attributes = array(
            'class' => 'btn btn-xs ' . $config['class'],
            'data-toggle' => 'tooltip',
            'title' => __(ucfirst($config['action']))
        );

        // A icon are the title of anchor
        $title = '<i class="'.$config['icon'].'"></i>';

        // Create
        return HTML::anchor($uri, $title, $attributes).' ';
    }
}