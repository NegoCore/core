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
 * Class Navigation_Abstract
 *
 * @property Navigation_Section $_section
 *
 * @property int $counter
 * @property string $name
 * @property string $url
 * @property string $permissions
 * @property int $priority
 * @property string $icon
 * @property array $buttons
 */
abstract class NegoCore_Navigation_Abstract {

    /**
     * Object params
     * @var array
     */
    protected $_params = array(
        'counter' => 0,
        'permissions' => null
    );

    /**
     * Section object
     * @var Navigation_Section
     */
    protected $_section;

    /**
     * Page buttons
     * @var array
     */
    protected $_buttons = array();

    /**
     * Store data to object
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach ($data as $key => $value)
        {
            $this->{$key} = $value;
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Magic method __get
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    // ----------------------------------------------------------------------

    /**
     * Magic method __set
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function __set($name, $value)
    {
        $this->_params[$name] = $value;

        return $this;
    }

    // ----------------------------------------------------------------------

    /**
     * Magic method __isset
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_params[$name]);
    }

    // ----------------------------------------------------------------------

    /**
     * Add buttons to page.
     *
     * @param array $buttons
     */
    public function add_buttons(array $buttons)
    {
        foreach ($buttons as $uri => $_buttons)
        {
            foreach ($_buttons as $button)
            {
                $this->_buttons[trim($uri, '/')][] = Navigation_Page::factory($button);
            }
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Fetch all buttons of this page.
     *
     * @return array
     */
    public function get_buttons()
    {
        $uri = Request::current()->uri();
        $uri = trim(str_replace(Request::current()->param(), '', $uri), '/');

        return isset($this->_buttons[$uri]) ? $this->_buttons[$uri] : array();
    }


    // ----------------------------------------------------------------------

    /**
     * Get param of object
     *
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return Arr::get($this->_params, $name, $default);
    }

    // ----------------------------------------------------------------------

    /**
     * Returns the section
     *
     * @return Navigation_Section
     */
    public function get_section()
    {
        return $this->_section;
    }

    // ----------------------------------------------------------------------

    /**
     * @param Navigation_Section $section
     * @return $this
     */
    public function set_section(Navigation_Section &$section)
    {
        $this->_section = $section;

        return $this;
    }

    // ----------------------------------------------------------------------

    /**
     * Check active status
     *
     * @return bool
     */
    public function is_active()
    {
        return (bool) Arr::get($this->_params, 'is_active', false);
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
        $this->_params['is_active'] = (bool) $status;

        if ($this->_section instanceof Navigation_Section)
        {
            $this->_section->set_active($status);
        }

        return $this;
    }

    // ----------------------------------------------------------------------

    /**
     * Get section parsed name
     *
     * @return string
     */
    public function get_name()
    {
        return __(Arr::get($this->_params, 'name'));
    }

    // ----------------------------------------------------------------------

    /**
     * Get title tag
     * @return string
     */
    public function get_title()
    {
        return __(Arr::get($this->_params, 'title', $this->get_name()));
    }

    // ----------------------------------------------------------------------

    /**
     * Get section counter
     *
     * @return int
     */
    public function get_counter()
    {
        return (int) Arr::get($this->_params, 'counter');
    }

    // ----------------------------------------------------------------------

    /**
     * Get section URL
     *
     * @param int $id Resource ID
     * @return string
     */
    public function get_url($id = null)
    {
        $url = Arr::get($this->_params, 'url');

        if ($url === null)
        {
            return '#';
        }

        return strtr(URL::site($url), array('<id>', $id));
    }
}