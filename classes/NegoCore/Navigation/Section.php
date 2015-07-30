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
 * Class Navigation_Section
 */
class NegoCore_Navigation_Section extends Navigation_Abstract {

    /**
     * Total pages of section
     * @var array
     */
    protected $_pages = array();

    /**
     * All sections
     * @var array
     */
    protected $_sections = array();

    /**
     * Factory a object
     *
     * @param array $data Options
     * @return Navigation_Section
     */
    public static function factory(array $data = array())
    {
        return new Navigation_Section($data);
    }

    // ----------------------------------------------------------------------

    /**
     * Get all pages
     *
     * @return array
     */
    public function get_pages()
    {
        return $this->_pages;
    }

    // ----------------------------------------------------------------------

    /**
     * Add page to section.
     *
     * @param Navigation_Abstract $page
     * @param int $priority
     * @return $this
     */
    public function add_page(Navigation_Abstract &$page, $priority = 1)
    {
        $priority = (int) $priority;

        // Check permissions
        if ( ! ACL::check($page->permissions))
        {
            return $this;
        }

        // Priority
        if (isset($page->priority))
        {
            $priority = (int) $page->priority;
        }

        // Typeof
        if ($page instanceof Navigation_Section)
        {
            $this->_sections[] = $page;
            $page->set_section($this);
        }
        else
        {
            $this->_pages[$this->get_priority($priority)] = $page;
        }

        // Add page buttons
        if (isset($page->buttons))
        {
            $page->add_buttons($page->buttons);
        }

        //
        $page->set_section($this);

        return $this->sort();
    }

    // ----------------------------------------------------------------------

    /**
     * Add page menu header
     *
     * @param array $header
     * @param int $priority
     */
    public function add_header(array $header, $priority = 1)
    {
        if (isset($header['priority']))
        {
            $priority = (int) $header['priority'];
        }

        $this->_pages[$this->get_priority($priority)] = $header;
    }

    // ----------------------------------------------------------------------

    /**
     * Add page menu separator
     *
     * @param array $separator
     * @param int $priority
     */
    public function add_separator(array $separator, $priority = 1)
    {
        if (isset($separator['priority']))
        {
            $priority = (int) $separator['priority'];
        }

        $this->_pages[$this->get_priority($priority)] = $separator;
    }

    // ----------------------------------------------------------------------

    /**
     * Add multiple pages to section
     *
     * @param array $pages
     */
    public function add_pages(array $pages)
    {
        foreach ($pages as $page)
        {
            if (isset($page['children']))
            {
                $section = Navigation::get_section($page['name'], $this);

                if (isset($page['icon']))
                {
                    $section->icon = $page['icon'];
                }

                // Buttons
                if (isset($section['buttons']))
                {
                    $section->add_buttons($section['buttons']);
                }

                // Children
                if (count($page['children']) > 0)
                {
                    $section->add_pages($page['children']);
                }
            }
            else if (isset($page['header']))
            {
                $this->add_header($page);
            }
            else if (isset($page['separator']))
            {
                $this->add_separator($page);
            }
            else
            {
                $page = Navigation_Page::factory($page);
                $this->add_page($page);
            }
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Get all sections
     *
     * @return array
     */
    public function get_sections()
    {
        return $this->_sections;
    }

    // ----------------------------------------------------------------------

    /**
     * Find a section in other
     *
     * @param string $name
     * @return Navigation_Section|null
     */
    public function find_section($name)
    {
        /**
         * @var $section Navigation_Section
         */

        // Search first level
        foreach ($this->_sections as $section)
        {
            if ($section->name == $name)
            {
                return $section;
            }
        }

        // Sub-levels
        foreach ($this->_sections as $section)
        {
            $found = $section->find_section($name);
            if ($found !== null)
            {
                return $found;
            }
        }

        return null;
    }

    // ----------------------------------------------------------------------

    /**
     * Get active page based on current URI
     *
     * @param string $uri
     * @return bool
     */
    public function find_active_page_by_uri($uri)
    {
        $found = false;

        // URI
        $length = strpos($uri, BACKEND_DIR_NAME);
        if ($length !== false)
        {
            $length += strlen(BACKEND_DIR_NAME);
        }

        $uri = substr($uri, $length);

        /**
         * Find in pages
         *
         * @var $page Navigation_Abstract
         */
        foreach ($this->get_pages() as $page)
        {
            // URL
            $url = $page->get_url();

            $length = strpos($url, BACKEND_DIR_NAME);
            if ($length !== false)
            {
                $length += strlen(BACKEND_DIR_NAME);
            }

            $url = substr($url, $length);

            //
            //if ( ! empty($url) && strpos($uri, $url) !== false)
            if( ! empty($url) && strpos($uri, $url) !== false || ($uri === false && $uri === $url))
            {
                $page->set_active();

                Navigation::$current = &$page;

                $found = true;

                break;
            }
        }

        /**
         * Find in sections
         *
         * @var $section Navigation_Section
         */
        if ($found === false)
        {
            foreach ($this->_sections as $section)
            {
                if (count($section->get_pages()) > 0)
                {
                    $found = $section->find_active_page_by_uri($uri);
                }

                if ($found === false)
                {
                    // URL
                    $url = $section->get_url();

                    $length = strpos($url, BACKEND_DIR_NAME);
                    if ($length !== false)
                    {
                        $length += strlen(BACKEND_DIR_NAME);
                    }

                    $url = substr($url, $length);

                    if ( ! empty($url) && strpos($uri, $url) !== false || ($uri === false && $uri === $url))
                    {
                        $section->set_active();

                        Navigation::$current = &$section;

                        $found = true;

                        break;
                    }
                }

                if ($found !== false)
                {
                    return $found;
                }
            }
        }

        return $found;
    }

    // ----------------------------------------------------------------------

    /**
     * Order sections based on priority
     *
     * @return $this
     */
    public function sort()
    {
        uasort($this->_sections, function ($a, $b) {
            if ($a->priority == $b->priority)
            {
                return 0;
            }

            return ($a->priority < $b->priority) ? -1 : 1;
        });

        ksort($this->_pages);

        return $this;
    }

    // ----------------------------------------------------------------------

    /**
     * Get section menu
     *
     * @return array
     */
    public function get_menu()
    {
        $menu_items = array();
        // Pages
        foreach ($this->get_pages() as $page)
        {
            $menu_items[$page->priority] = $page;
        }

        // Sections
        foreach ($this->get_sections() as $section)
        {
            $menu_items[$section->priority] = $section;
        }

        ksort($menu_items);

        return $menu_items;
    }

    // ----------------------------------------------------------------------

    /**
     * Get current page priority
     *
     * @param int $priority
     * @return int
     */
    public function get_priority($priority = 1)
    {
        // Change priority
        if (isset($this->_pages[$priority]))
        {
            while (isset($this->_pages[$priority]))
            {
                $priority++;
            }
        }

        return $priority;
    }
}