<?php
class RestRequest{
	
	private $request_vars;  
    private $data;  
    private $http_accept;  
    private $method;  
  
    public function __construct()  
    {  
        $this->request_vars      = array();  
        $this->data              = '';  
        $this->http_accept       = $_SERVER['HTTP_ACCEPT'];  
        $this->method            = 'get';  
    }  
  
    public function setData($data)  
    {  
        $this->data = $data;  
    }  
  
    /*
	 * a method would be a string either 'put' 'post' or 'get'
	 */
    public function setMethod($method)  
    {  
        $this->method = $method;  
    }  
  
    /*
	 * the input is usually either $_GET, $_POST or arrays of variables for the put method
	 */
    public function setRequestVars($request_vars)  
    {  
        $this->request_vars = $request_vars;  
    }  
  
    public function getData()  
    {  
        return $this->data;  
    }  
  
    public function getMethod()  
    {  
        return $this->method;  
    }  
  
    public function getHttpAccept()  
    {  
        return $this->http_accept;  
    }  
  
    public function getRequestVars()  
    {  
        return $this->request_vars;  
    }  
}  

?>