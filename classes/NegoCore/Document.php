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
    public static $title;

    /**
     * Document Metadata
     *
     * @var array
     */
    public static $metadata;

    /**
     * Get or Set metadata
     *
     * @param string $title
     * @return string
     */
    public static function title($title = null)
    {
        if ($title !== null)
        {
            $site = Kohana::$config->load('site.title');
            if (is_array($site))
            {
                $name = isset($site['name']) ? $site['name'] : '';
                $separator = isset($site['separator']) ? $site['separator'] : '|';
                $dir = isset($site['dir']) ? $site['dir'] : 'right';

                if ($dir == 'left')
                {
                    $title = $name.' '.$separator.' '.$title;
                }
                else
                {
                    $title .= ' '.$separator.' '.$name;
                }
            }

            Document::$title = HTML::chars($title);
        }

        return Document::$title;
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

        return Document::$metadata[$handle] = array(
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
        if (empty(Document::$metadata))
        {
            return false;
        }

        $metadata = array();
        foreach (Document::$metadata as $handle => $data)
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
        if ( ! isset(Document::$metadata[$handle]))
        {
            return false;
        }

        $meta = Document::$metadata[$handle];

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
            Document::$metadata = array();
            return;
        }

        unset(Document::$metadata[$handle]);
    }

}