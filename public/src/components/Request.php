<?php 
namespace components;


class Request {
    
    
    function __construct() 
    {
        
    }
    
    
    public function getQueryParam($name, $default = null)
    {
        $params = $this->getQueryParams();
        return isset($params[$name]) ? $params[$name] : $default;
    }

    public function getQueryParams()
    {
        return $_GET;
    }
    
    public function getPathInfo()
    {
        return $_SERVER['PATH_INFO'];
    }
    
    
}