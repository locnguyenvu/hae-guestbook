<?php
namespace Hae\Core;

class HttpRequest
{
    public $requestMethod;
    public $requestUri;
    public $requestHeaders;
    public $rawRequestBody;
    public $getParams;

    public function __construct()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->rawRequestBody = \file_get_contents('php://input');
        $this->requestUri = $_SERVER['REQUEST_URI'];
        $this->getParams = $_GET;

        $this->requestHeaders = getallheaders();
    }

    public function getRawBody()
    {
        $rawData = \file_get_contents('php://input');
        return $rawData;
    }

    public function getParam(string $key) {
        return $this->getParams[$key] ?? null;
    }

    public function getParams() : array
    {
        return $this->getParams;
    }

    public function getHeader(string $key) : string
    {
        return $this->requestHeaders[$key] ?? null;
    }

    public function hasHeader(string $key) :  bool
    {
        return isset($this->requestHeaders[$key]);
    }


}