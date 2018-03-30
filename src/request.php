<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 12.03.18
 * Time: 15:40
 */

class Request
{
    function __construct()
    {
        $this->request = $_REQUEST;

        $get_index = strpos($_SERVER['REQUEST_URI'], '?');
        $get_index = $get_index ? $get_index : strlen($_SERVER['REQUEST_URI']);

        $this->location = explode('/',
           trim(substr($_SERVER["REQUEST_URI"], 0, $get_index), '/'));
    }

    public $location = array();
    public $request;
}