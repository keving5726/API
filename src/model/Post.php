<?php

namespace Src\Model;

use Config\DBConnection;

class Post extends DBConnection implements IRepository
{
    private $obj;
    private $connection;
    private $query;
    private $exec;
    private $result;
    private $otro;
    
    public function __construct()
    {
        $this->obj = new DBConnection();
        $this->connection = $this->obj->connect();
    }

    public function findAll(): ?array
    {
        $this->query = "SELECT * FROM post ORDER BY updated_at ASC;";
        if ($this->exec = $this->connection->query($this->query))
        {
            while ($row = $this->exec->fetch_object())
            {
                $this->result[] = $row;
            }
            $this->exec->free();
        }
        return $this->result;
    }

    public function findBy(string $criteria, string $value): ?array
    {
        $this->query = "SELECT * FROM post WHERE $criteria = '$value';";
        if ($this->exec = $this->connection->query($this->query))
        {
            while ($row = $this->exec->fetch_object())
            {
                $this->result[] = $row;
            }
            $this->exec->free();
        }
        return $this->result;
    }

    public function findByCreated(string $id, string $created): ?object
    {
        $this->query = "SELECT * FROM post WHERE id_user = '$id' AND created_at = '$created';";
        if ($this->exec = $this->connection->query($this->query))
        {
            while ($row = $this->exec->fetch_object())
            {
                $this->result = $row;
            }
            $this->exec->free();
        }
        return $this->result;
    }

    public function findById(string $id_user, string $id): ?object
    {
        $this->result = null;
        $this->query = "SELECT * FROM post WHERE id_user = '$id_user' AND id = '$id';";
        if ($this->exec = $this->connection->query($this->query))
        {
            while ($row = $this->exec->fetch_object())
            {
                $this->result = $row;
            }
            $this->exec->free();
        }
        return $this->result;
    }

    public function create(string $id, string $title, string $description, string $datetime): ?bool
    {
        $this->query = "INSERT INTO post(id_user, title, description, created_at, updated_at) VALUES ('$id', '$title', '$description', '$datetime', '$datetime');";
        if ($this->exec = $this->connection->query($this->query))
        {
            return true;
        }
        return false;
    }

    public function update(string $id, string $title, string $description, string $datetime): ?bool
    {
        $this->query = "UPDATE post SET title = '$title', description = '$description', updated_at = '$datetime' WHERE id = '$id';";
        if ($this->exec = $this->connection->query($this->query))
        {
            return true;
        }
        return false;
    }

    public function delete(string $id_user, string $id): ?bool
    {
        $this->query = "DELETE from post WHERE id_user = '$id_user' AND id = '$id';";
        if ($this->exec = $this->connection->query($this->query))
        {
            return true;
        }
        return false;
    }
}
