<?php
namespace Hae\GuestBook;

use Hae\Core\Entity;

class GuestBook implements Entity
{
    protected $id;
    protected $username;
    protected $content;
    protected $created_at;
    protected $is_deleted;

    public function assign(array $properties)
    {
        foreach ($properties as $key => $value) {
            if (!\property_exists($this, $key)) { continue; }
            $this->$key = $value;
        }
    }

    public function getId() : int
    {
        return intval($this->id);
    }

    public function getUsername() : ?string
    {
        return $this->username;
    }

    public function getContent() : ?string
    {
        return $this->content;
    }

    public function getCreatedAt() : ?string
    {
        if (empty($this->created_at)) {
            $this->created_at = date('Y-m-d');
        }
        return $this->created_at;
    }

    public function getIsDeleted() : int
    {
        return intval($this->is_deleted);
    }

    public function toArray() : array
    {
        return [
            'id' => intval($this->id),
            'username' => $this->username,
            'content' => $this->content,
            'created_at' => $this->created_at
        ];
    }

    public function setContent(string $content) : void
    {
        $this->content = trim($content);
    }

    public function setUsername(string $username) : void
    {
        $this->username = trim($username);
    }
}