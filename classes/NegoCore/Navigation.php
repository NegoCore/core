<?php defined('NEGOCORE') or die('No direct script access.');

/**
 * NegoCore
 *
 * @author      Iván Molina Pavana <imp@negocad.mx>
 * @copyright   Copyright (c) 2015, NegoCAD <info@negocad.mx>
 * @version     1.0.0
 */

// --------------------------------------------------------------------------------

class NegoCore_Navigation {

    /**
     * Root section
     * @var Navigation_Section
     */
    protected static $_root_section;

    /**
     * Current page
     * @var Navigation_Page
     */
    public static $current;

    /**
     * Initialize navigation system
     *
     * @param array $sitemap
     */
    public static function init(array $sitemap)
    {
        foreach ($sitemap as $section)
        {
            // Invalid
            if ( ! isset($section['name']))
            {
                continue;
            }

            // Add page
            if (isset($section['url']) && ! isset($section['top']))
            {//var_dump($section);
                $page = Navigation_Page::factory($section);

                $section_object = Navigation::get_root_section();
                $section_object->add_page($page);
            }
            // Add section
            else
            {
                $section_object = Navigation::get_section($section['name'], null, isset($section['priority']) ? $section['priority'] : 1);

                // URL
                if (isset($section['url']))
                {
                    $section_object->url = $section['url'];
                }

                // Icon
                if (isset($section['icon']))
                {
                    $section_object->icon = $section['icon'];
                }

                // Buttons
                if (isset($section['buttons']))
                {
                    $section_object->add_buttons($section['buttons']);
                }

                // Priority
                if (isset($section['priority']))
                {
                    $section_object->priority = (int) $section['priority'];
                }

                if ( ! empty($section['children']))
                {
                    $section_object->add_pages($section['children']);
                }
            }
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Creates a root section
     *
     * @return Navigation_Section
     */
    public static function get_root_section()
    {
        if (Navigation::$_root_section === null)
        {
            Navigation::$_root_section = Navigation_Section::factory(array(
                'name' => 'root'
            ));
        }

        return Navigation::$_root_section;
    }

    // ----------------------------------------------------------------------

    /**
     * Loads section by name
     *
     * @param string $name Name of section
     * @param Navigation_Section $parent If section has parent section
     * @param int $priority
     * @return Navigation_Section|null
     */
    public static function get_section($name, Navigation_Section $parent = null, $priority = 1)
    {
        if ($parent === null)
        {
            $parent = Navigation::get_root_section();
        }

        $section = $parent->find_section($name);

        if ($section === null)
        {
            $section = Navigation_Section::factory(array(
                'name' => $name,
                'priority' => $priority
            ));

            $parent->add_page($section);
        }

        return $section;
    }

    // ----------------------------------------------------------------------

    /**
     * Get section
     *
     * @param string $uri
     * @return Navigation_Section
     */
    public static function get($uri = null)
    {
        if ($uri === null)
        {
            $uri = Request::current()->uri();
        }

        $uri = strtolower($uri);

        Navigation::$_root_section->find_active_page_by_uri($uri);
        Navigation::$_root_section->sort();

        return Navigation::$_root_section;
    }
}