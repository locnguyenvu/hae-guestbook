<?php
namespace App\Controller;

abstract class AbstractController
{
    protected $request;
    protected $response;
    
    public function __construct()
    {
        $this->request = \App\WebApp::$di->get('request');
        $this->response = \App\WebApp::$di->get('response');
        $this->init();
    }

    protected function init() {}

    public function isAuthorized() : bool
    {
        if (!$this->request->hasHeader('Authorization')) {
            return false;
        }

        $base64String = ltrim($this->request->getHeader('Authorization'), "Basic ");
        $usernamePassowdPattern = base64_decode($base64String);

        extract(wapp('config')->get('authentication'));

        return $usernamePassowdPattern == "{$username}:{$password}";
    }

    public function respondSuccess()
    {
        return ['status' => 'Success'];
    }

    public function respondUnauthorized()
    {
        $this->response->setResponseCode(401);
        return ['message' => 'Unauthorized'];
    }
}