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
 * Class NegoCore_Messages
 */
class NegoCore_Messages {

    // Message Types
    const VALIDATION    = 'validation';
    const SUCCESS       = 'success';
    const WARNING       = 'warning';
    const ERROR         = 'danger';

    /**
     * @var array Messages
     */
    protected static $_data = array();

    /**
     * @var string Session key
     */
    protected static $_session_key = 'message';

    /**
     * Get all message types
     *
     * @return array
     */
    public static function types()
    {
        return array(
            Messages::SUCCESS,
            Messages::WARNING,
            Messages::ERROR
        );
    }

    // ----------------------------------------------------------------------

    /**
     * Set a message
     *
     * @param string $type Type of message
     * @param array|string $message Message or message list.
     * @param array $values Values to replace in message
     * @return bool
     */
    public static function set($type, $message, array $values = null)
    {
        if ( ! is_array($message))
        {
            $message = array($message);
        }

        foreach ($message as $key => $string)
        {
            $message[$key] = empty($values) ? $string : strtr($string, $values);
        }

        self::$_data[$type] = ! empty(self::$_data[$type]) ? Arr::merge(self::$_data[$type], $message) : $message;

        Session::instance()->set(self::$_session_key.'::'.$type, self::$_data[$type]);

        return true;
    }

    // ----------------------------------------------------------------------

    /**
     * Get one or all messages.
     *
     * @param string $type
     * @return array|mixed
     */
    public static function get($type = null)
    {
        $session = Session::instance();

        if ($type === null)
        {
            $array = array();

            foreach (self::types() as $key => $type)
            {
                $array[$type] = $session->get_once(self::$_session_key.'::'.$type, array());
            }

            return $array;
        }

        return $session->get_once(self::$_session_key.'::'.$type, array());
    }

    // ----------------------------------------------------------------------

    /**
     * Get or Set a success message
     *
     * @param array|string $message
     * @param array $values
     * @return mixed
     */
    public static function success($message = null, $values = null)
    {
        if ($message === null)
            return self::get(Messages::SUCCESS);

        return Messages::set(Messages::SUCCESS, $message, $values);
    }

    // ----------------------------------------------------------------------

    /**
     * Get or Set a warning message
     *
     * @param array|string $message
     * @param array $values
     * @return mixed
     */
    public static function warning($message = null, $values = null)
    {
        if ($message === null)
            return self::get(Messages::WARNING);

        return Messages::set(Messages::WARNING, $message, $values);
    }

    // ----------------------------------------------------------------------

    /**
     * Get or set a error message
     *
     * @param array|string $message
     * @param array $values
     * @return mixed
     */
    public static function error($message = null, $values = null)
    {
        if ($message === null)
            return self::get(Messages::ERROR);

        return Messages::set(Messages::ERROR, $message, $values);
    }

    // ----------------------------------------------------------------------

    /**
     * Set validation errors.
     *
     * @param ORM_Validation_Exception|Validation $validation
     * @param string $file File or directory to error messages.
     * @return mixed
     */
    public static function validation($validation, $file = 'models')
    {
        $messages = $validation->errors($file);

        self::$_data[Messages::VALIDATION] = ! empty(self::$_data[Messages::VALIDATION]) ? Arr::merge(self::$_data[Messages::VALIDATION], $messages) : $messages;
    }

    // ----------------------------------------------------------------------

    /**
     * Get form error field.
     *
     * @param string $field
     * @return mixed
     */
    public static function form_error($field = null)
    {
        if ($field === null)
            return isset(self::$_data[Messages::VALIDATION]) ? self::$_data[Messages::VALIDATION] : null;

        return isset(self::$_data[Messages::VALIDATION][$field]) ? self::$_data[Messages::VALIDATION][$field] : null;
    }
}