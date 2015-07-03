<?php defined('NEGOCORE') or die('No direct script access.');

/**
 * NegoCore
 *
 * @author      Iván Molina Pavana <imp@negocad.mx>
 * @copyright   Copyright (c) 2015, NegoCAD <info@negocad.mx>
 * @version     1.0.0
 */

// --------------------------------------------------------------------------------

class NegoCore_HTTP_Exception extends Kohana_HTTP_Exception {

    /**
     * NegoCore HTTP Error handler
     *
     * @return Response
     */
    public function get_response()
    {
        if (Kohana::$environment == Kohana::DEVELOPMENT)
        {
            return parent::get_response();
        }
        else
        {
            $params = array(
                'code' => $this->getCode(),
                'message' => rawurlencode($this->getMessage())
            );

            try {

                $request = Request::factory(Route::get('error')->uri($params), array(), false)
                    ->execute()
                    ->send_headers(true)
                    ->body();

                return Response::factory()
                    ->status($this->getCode())
                    ->body($request);

            } catch (Exception $e) {
                return parent::get_response();
            }
        }
    }
}