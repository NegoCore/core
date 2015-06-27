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
 * Class NegoCore_Document
 *
 * Used to configure HTML document, this class is static because only process one
 * HTML document per request.
 */
class NegoCore_Document {

    /**
     * Document title
     *
     * @var string
     */
    protected static $_title;

    /**
     * Document Metadata
     *
     * @var array
     */
    protected static $_meta_data;

    /**
     * Set document title
     *
     * @param string $title
     * @throws Kohana_Exception
     */
    public static function title($title = '')
    {
        $site = Kohana::$config->load('site.title');
        if (is_array($site))
        {
            $name = isset($site['name']) ? $site['name'] : '';
            $separator = isset($site['separator']) ? $site['separator'] : '|';
            $dir = isset($site['dir']) ? $site['dir'] : 'right';

            if ( ! empty($title))
            {
                if ($dir == 'left')
                {
                    $title = $name.' '.$separator.' '.$title;
                }
                else
                {
                    $title .= ' '.$separator.' '.$name;
                }
            }
            else
            {
                $title = $name;
            }
        }

        self::$_title = HTML::chars($title);
    }

    // ----------------------------------------------------------------------

    /**
     * Get document title
     *
     * @param bool $html True returns HTML title tag
     * @return string
     */
    public static function get_title($html = false)
    {
        return $html == true ? '<title>'.self::$_title.'</title>' : self::$_title;
    }

    // ----------------------------------------------------------------------

    /**
     * Get or Set metadata
     *
     * @param bool|mixed $handle
     * @param array $attributes
     * @return array
     */
    public static function meta($handle = null, array $attributes = null)
    {
        if ($handle === null)
        {
            return Document::all_meta($handle);
        }

        if ($attributes === null)
        {
            return Document::get_meta($handle);
        }

        return self::$_meta_data[$handle] = array(
            'attributes' => $attributes,
            'handle' => $handle,
            'type' => 'meta'
        );
    }

    // ----------------------------------------------------------------------

    /**
     * Get all metadata
     *
     * @return bool|string
     */
    public static function all_meta()
    {
        if (empty(self::$_meta_data))
        {
            return false;
        }

        $metadata = array();
        foreach (self::$_meta_data as $handle => $data)
        {
            $metadata[] = Document::get_meta($handle);
        }

        return implode("\n    ", $metadata);
    }

    // ----------------------------------------------------------------------

    /**
     * Get single metadata
     *
     * @param string $handle
     * @return string
     */
    public static function get_meta($handle)
    {
        if ( ! isset(self::$_meta_data[$handle]))
        {
            return false;
        }

        $meta = self::$_meta_data[$handle];

        return '<meta' . HTML::attributes($meta['attributes']) . ' />';
    }

    // ----------------------------------------------------------------------

    /**
     * Remove a metadata, or all
     *
     * @param mixed $handle
     */
    public static function remove_meta($handle = null)
    {
        // Remove all
        if ($handle === null)
        {
            self::$_meta_data = array();
            return;
        }

        unset(self::$_meta_data[$handle]);
    }

}