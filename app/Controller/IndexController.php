<?php

namespace App\Controller;

use Hae\Core\HttpRequest;

class IndexController extends AbstractController
{
    public function home(HttpRequest $request)
    {
        return 'Hello there';
    }

    public function login(HttpRequest $request)
    {
        $login = json_decode($request->getRawBody(), true);
        $loginString = "{$login['username']}:{$login['password']}";
        
        extract(wapp('config')->get('authentication'));
        
        if ("{$username}:{$password}" == $loginString) {
            return ['token' => base64_encode($loginString)];
        }
        return $this->respondUnauthorized();

    }
}