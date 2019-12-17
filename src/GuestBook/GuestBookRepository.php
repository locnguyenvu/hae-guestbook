<?php
namespace Hae\GuestBook;

class GuestBookRepository extends \Hae\Core\Repository
{
    function getEntity() : string
    {
        return GuestBook::class;
    }

    public function store(GuestBook $entity)
    {
        $pre = $this->conn->prepare("INSERT INTO `{$this->getTable()}` (`username`, `content`, `created_at`, `is_deleted`) VALUES (:username, :content, :created_at, :is_deleted)");
        $pre->bindValue(':username', $entity->getUsername(), \PDO::PARAM_STR);
        $pre->bindValue(':content', $entity->getContent(), \PDO::PARAM_STR);
        $pre->bindValue(':created_at', $entity->getCreatedAt(), \PDO::PARAM_STR);
        $pre->bindValue(':is_deleted', $entity->getIsDeleted(), \PDO::PARAM_INT);
        $pre->execute() or die(print_r([
            'db_error' => $pre->errorInfo()
        ], true)); // incorrect;
    }

    public function list($newest = true) : array
    {
        $sql = "SELECT * FROM `{$this->getTable()}` WHERE is_deleted = 0 ORDER BY id ".($newest ? 'desc':'asc');
        $dataSet = $this->conn->query($sql);
        $result = [];
        $entityClass = $this->getEntity();
        while($row = $dataSet->fetch(\PDO::FETCH_ASSOC)) {
            $entity = new $entityClass;
            $entity->assign($row);
            $result[] = $entity;
        }        
        return $result;
    }

    public function delete(int $id)
    {
        $sql = "UPDATE `{$this->getTable()}` SET `is_deleted` = 1 WHERE id = ?";
        $pre = $this->conn->prepare($sql);
        $pre->execute([$id]) or die(print_r([
            'db_error' => $pre->errorInfo()
        ], true)); // incorrect;
    }

    public function update(GuestBook $entity) {
        $pre = $this->conn->prepare("UPDATE `{$this->getTable()}` SET content = :content WHERE id = :id");
        $pre->bindValue(':content', $entity->getContent(), \PDO::PARAM_STR);
        $pre->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
        $pre->execute() or die(print_r([
            'db_error' => $pre->errorInfo()
        ], true)); // incorrect;
    }

    public function findById(int $id) : GuestBook
    {
        $entity = new GuestBook;
        
        $select = $this->conn->prepare("SELECT * FROM `{$this->getTable()}` WHERE id = :id");
        $select->bindValue(':id', $id, \PDO::PARAM_INT);
        $select->execute();
        $row = $select->fetch(\PDO::FETCH_ASSOC);
        if (is_array($row)) {
            $entity->assign($row);
        }
        return $entity;
    }
}