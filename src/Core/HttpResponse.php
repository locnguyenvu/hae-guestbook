<?php
namespace Hae\Core;

class HttpResponse
{
    protected $resposneCode = 200;
    protected $resposneData = null;

    function setResponseData($data) : void
    {
        $this->resposneData = $data;
    }

    function setResponseCode(int $code) : void
    {
        $this->resposneCode = $code;
    }
    

    function sendError404() : void
    {
        http_response_code(404);
        echo 'Route not found';
    }

    function __destruct()
    {
        http_response_code($this->resposneCode);
        if (empty($this->resposneData)) {
            return;
        }
        if (is_array($this->resposneData)) {
            header('Content-Type: application/json');
            echo json_encode($this->resposneData);
            return;
        }        
        print_r($this->resposneData);
    }
}