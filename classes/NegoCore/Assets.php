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
 * Class NegoCore_Assets
 */
class NegoCore_Assets {

    /**
     * @var array CSS assets
     */
    protected static $_css = array();

    /**
     * @var array JavaScript assets
     */
    protected static $_js = array();

    /**
     * Get or Set CSS assets
     *
     * @param string $handle
     * @param string $src
     * @param mixed $deps
     * @param array $attributes
     * @param string $module
     * @return array|bool|string
     */
    public static function css($handle = null, $src = null, $deps = null, $attributes = null, $module = null)
    {
        // Return all CSS assets, sorted by dependencies
        if ($handle === null)
        {
            return Assets::all_css();
        }

        // Return individual asset
        if ($src === null)
        {
            return Assets::get_css($handle);
        }

        // Set default media attribute
        if ( ! isset($attributes['media']))
        {
            $attributes['media'] = 'all';
        }

        return self::$_css[$handle] = array(
            'src' => strpos($src, '//') !== false ? $src : URL::site(($module !== null ? 'modules/'.$module : '') . '/assets/css/' . $src),
            'deps' => (array) $deps,
            'attrs' => $attributes,
            'handle' => $handle,
            'type' => 'css'
        );
    }

    // ----------------------------------------------------------------------

    /**
     * Get all CSS assets, sorted by dependencies
     *
     * @return bool|string
     */
    public static function all_css()
    {
        if (empty(self::$_css))
        {
            return false;
        }

        $assets = array();
        foreach (Assets::_sort(self::$_css) as $handle => $data)
        {
            $assets[] = Assets::get_css($handle);
        }

        return implode("\n    ", $assets);
    }

    // ----------------------------------------------------------------------

    /**
     * Get single CSS asset
     *
     * @param $handle
     * @return bool|string
     */
    public static function get_css($handle)
    {
        if ( ! isset(self::$_css[$handle]))
        {
            return false;
        }

        $asset = self::$_css[$handle];

        return HTML::style($asset['src'], $asset['attrs']);
    }

    // ----------------------------------------------------------------------

    /**
     * Remove a CSS asset, or all
     *
     * @param string|null $handle
     */
    public static function remove_css($handle = null)
    {
        // Remove all
        if ($handle === null)
        {
            self::$_css = array();
            return;
        }

        unset(self::$_css[$handle]);
    }

    // ----------------------------------------------------------------------

    /**
     * Get or Set JS asset
     *
     * @param bool|mixed $handle
     * @param string $src
     * @param array $deps
     * @param bool $footer
     * @param string $module
     * @return array
     */
    public static function js($handle = false, $src = null, $deps = null, $footer = false, $module = null)
    {
        if (is_bool($handle))
        {
            return Assets::all_js($handle);
        }

        if ($src === null)
        {
            return Assets::get_js($handle);
        }

        return self::$_js[$handle] = array(
            'src' => strpos($src, '//') !== false ? $src : URL::site(($module !== null ? 'modules/'.$module : '') . '/assets/js/' . $src),
            'deps' => (array) $deps,
            'footer' => (bool) $footer,
            'handle' => $handle,
            'type' => 'js'
        );
    }

    // ----------------------------------------------------------------------

    /**
     * Get all JS assets
     *
     * @param bool $footer
     * @return bool|string
     */
    public static function all_js($footer = false)
    {
        if (empty(self::$_js))
        {
            return false;
        }

        $assets = array();
        foreach (self::$_js as $handle => $data)
        {
            if ($data['footer'] === $footer)
            {
                $assets[$handle] = $data;
            }
        }

        if (empty($assets))
        {
            return false;
        }

        $sorted = array();
        foreach (Assets::_sort($assets) as $handle => $data)
        {
            $sorted[] = Assets::get_js($handle);
        }

        return implode("\n    ", $sorted);
    }

    // ----------------------------------------------------------------------

    /**
     * Get single JS asset
     *
     * @param string $handle
     * @return string
     */
    public static function get_js($handle)
    {
        if ( ! isset(self::$_js[$handle]))
        {
            return false;
        }

        $asset = self::$_js[$handle];

        return HTML::script($asset['src']);
    }

    // ----------------------------------------------------------------------

    /**
     * Remove a JS asset, or all
     *
     * @param mixed $handle
     */
    public static function remove_js($handle = null)
    {
        if ($handle === null)
        {
            self::$_js = array();
            return;
        }

        if (is_bool($handle))
        {
            foreach (self::$_js as $handle => $data)
            {
                if ($data['footer'] === $handle)
                {
                    unset(self::$_js[$handle]);
                }
            }

            return;
        }

        unset(self::$_js[$handle]);
    }

    // ----------------------------------------------------------------------

    /**
     * Get image URL or HTML tag
     *
     * @param string $file Image name with extension.
     * @param bool $html Return as HTML tag
     * @param array|string $attributes String is alt text of IMG tag.
     * @return string
     */
    public static function image($file, $html = false, $attributes = null, $module = null)
    {
        // Module method
        if ( ! is_bool($html))
        {
            $params = array_pad(func_get_args(), 4, null);
            $params[2] = (bool) $params[2];

            return call_user_func_array('Assets::image', array($params[1], $params[2], $params[3], $params[0]));
        }

        // File URL
        $file = URL::site(($module !== null ? $module : 'application').'/assets/img/' . $file);

        // HTML Tag?
        if ($html == true)
        {
            $attrs = array();

            // Alt text
            if ( ! is_array($attributes))
            {
                $attrs['alt'] = $attributes;
            }
            else
            {
                $attrs = $attributes;
            }

            return HTML::image($file, $attrs);
        }

        return $file;
    }

    // ----------------------------------------------------------------------

    /**
     * Load module assets
     *
     * @param string $module
     * @param array $resources
     * @param string $type
     */
    public static function module($module, array $resources, $type = 'auto')
    {
        if ( ! is_array($resources[0]))
        {
            $resources = array($resources);
        }

        // Add all module assets
        foreach ($resources as $el)
        {
            // Get parts
            list($handle, $src, $deps, $attributes) = array_pad($el, 4, null);

            // Auto-detect type
            if ($type == 'auto')
            {
                $type = explode('.', $src);
                $type = end($type);
            }

            // Add assets
            if ($type == 'css')
            {
                Assets::css($handle, $src, $deps, $attributes, $module);
            }
            elseif ($type == 'js')
            {
                Assets::js($handle, $src, $deps, $attributes, $module);
            }
        }
    }

    // ----------------------------------------------------------------------

    /**
     * Sorts assets based on dependencies
     *
     * @param array $assets
     * @return array
     */
    protected static function _sort($assets)
    {
        $original = $assets;
        $sorted = array();

        while (count($assets) > 0)
        {
            foreach ($assets as $key => $value)
            {
                // No dependencies anymore, add it to sorted
                if (empty($assets[$key]['deps']))
                {
                    $sorted[$key] = $value;
                    unset($assets[$key]);
                }
                else
                {
                    foreach ($assets[$key]['deps'] as $k => $v)
                    {
                        // Remove dependency if doesn't exist, if its dependent on itself, or if the dependent is dependent on it
                        if (!isset($original[$v]) OR $v === $key OR ( isset($assets[$v]) AND in_array($key, $assets[$v]['deps'])))
                        {
                            unset($assets[$key]['deps'][$k]);
                            continue;
                        }

                        // This dependency hasn't been sorted yet
                        if (!isset($sorted[$v]))
                            continue;

                        // This dependency is taken care of, remove from list
                        unset($assets[$key]['deps'][$k]);
                    }
                }
            }
        }

        return $sorted;
    }
}