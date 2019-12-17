<?php
namespace Hae\Core;

use Hae\Exception\{
    RouteNotFoundException,
    MethodNotAllowedException
};

class Router
{
    private $request;
    private $response;
    

    function __construct(HttpRequest $request, HttpResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    function __call($name, $args)
    {
        list($route, $method) = $args;
        
        if (!in_array($name, ['get', 'post', 'delete', 'put'])) {
            throw new MethodNotAllowedException();
        }

        $this->{$name}[$this->formatRoute($route)] = $method;
    }

    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '')
        {
            return '/';
        }
        return $result;
    }

    function resolve()
    {
        $requestMethod = \strtolower($this->request->requestMethod);
        $routeWithoutUirParams = preg_replace('/\?.*$/', '', $this->request->requestUri);
        $dictionary = $this->{$requestMethod};

        foreach ($dictionary as $route => $handler) {
            if (!$this->match($route, $routeWithoutUirParams)) {
                continue;
            }
            
            if (is_array($handler)) {
                list($controllerClass, $method) = $handler;
                $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
                $classMethodArguments = $this->getRouteParams($route, $routeWithoutUirParams);
                array_unshift($classMethodArguments, $this->request);
                $responseData = $reflectionMethod->invokeArgs(new $controllerClass, $classMethodArguments);
            } elseif (is_callable($handler)) {
                $responseData = \call_user_func($handler);
            }
            $this->response->setResponseData($responseData);
            return;
        }
        throw new RouteNotFoundException;

    }

    function __destruct()
    {
        $this->resolve();
    }

    public function match($pattern, $requestUri) {
        $partenChunks = explode('/', $pattern);
        $regexElements = [];
        foreach ($partenChunks as $chunk) {
            if (empty($chunk)) {
                continue;
            }
            if (preg_match('/\{.*\}/', $chunk)) {
                $regexElements[] = '.+';
            } else {
                $regexElements[] = $chunk;
            }
        }
        $regexPattern = implode('\/', $regexElements);
        return preg_match('/^\/'.$regexPattern.'$/', $requestUri);
    }

    public function getRouteParams(string $routePattern, string $routeActual)
    {
        $routeParams = [];
        $chunksPattern = explode('/', $routePattern);
        $chunksActual = explode('/', $routeActual);
        for ($i = 0; $i < count($chunksPattern); $i++) {
            if (preg_match('/\{.*\}/', $chunksPattern[$i])) {
                $key = trim($chunksPattern[$i], '\{\}');
                $routeParams[$key] = $chunksActual[$i];
            }
        }
        return $routeParams;
    }

}