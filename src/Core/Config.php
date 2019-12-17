<?php
namespace Hae\Core;

class Config {
    private $data;

    public function __construct()
    {
        $this->data = include(ROOT_PATH.'/config.php');
    }

    public function get(string $key) {
        return array_get($this->data, $key);
    }

    public function getAll()
    {
        return $this->data;
    }
}