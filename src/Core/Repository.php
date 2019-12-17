<?php
namespace Hae\Core;

abstract class Repository
{
    protected $conn;
    protected $table = null;

    final public function __construct(DatabaseConnection $conn) {
        $this->conn = $conn;
    }

    abstract function getEntity() : string;

    function setConnection(DatabaseConnection $conn) {
        $this->conn = $conn;
    }

    function getConnection() : DatabaseConnection
    {
        return $this->conn;
    }

    public function getTable() : string
    {
        if ($this->table == null) {
            $entityClassChunks = explode('\\', $this->getEntity());
            $entityClassWithoutNamespace = array_pop($entityClassChunks);
            $this->table = string_snakelize($entityClassWithoutNamespace);
        }
        return $this->table;
    }

    public function quote($string) {
        return '\''.trim($string).'\'';
    }
}