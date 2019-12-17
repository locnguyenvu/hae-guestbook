<?php
namespace Hae;

use Hae\Container\Reference\{
    ParameterReference,
    ServiceReference
};
use Hae\Exception\{
    ContainerException,
    ParameterNotFoundException,
    ServiceNotFoundException
};

class Container
{
    private $services = [];
    private $alias = [];
    private $parameters = [];
    private $binds = [];
    private $serviceStore;

    public function __construct()
    {
        $this->load();
    }

    private function load() : void
    {
        $bootstraps = include(ROOT_PATH.'/bootstrap.php');
        $services = array_get($bootstraps, 'services');
        $this->binds = array_get($bootstraps, 'binds');
        foreach ($services as $alias => $construction) {
            
            $arguments = $construction['arguments'] ?? [];
            $reflector = new \ReflectionClass($construction['class']);
            $service = $reflector->newInstanceArgs($arguments);
            
            $this->services[] = $alias;
            $this->serviceAliasMap[get_class($service)] = $alias;
            $this->serviceStore[$alias] = $service;
        }
    }

    public function get(string $name) {
        if (!$this->has($name)) {
            throw new ServiceNotFoundException();
        }
        return $this->serviceStore[$name];
    }

    public function has(string $name) {
        return in_array($name, $this->services);
    }

    public function create(string $className) {
        $reflector = new \ReflectionClass($className);
        $constructorParams = $reflector->getConstructor()->getParameters();
        
        $dependencies = [];
        foreach ($constructorParams as $param) {
            $dependencies[] = $this->constructParam($param);
        }

        $instance = $reflector->newInstanceArgs($dependencies);
        return $instance;
    }

    private function constructParam(\ReflectionParameter $param) {
        $paramClassName = $param->getClass()->name;
        if (isset($this->serviceAliasMap[$paramClassName])) {
            $instance = $this->get($this->serviceAliasMap[$paramClassName]);
        } else {
            $instance = new $param->getClass();
        }
        return $instance;
    }

}
