<?php

namespace Src\Model;

use Config\DBConnection;

class User extends DBConnection implements IRepository
{
    private $obj;
    private $connection;
    private $query;
    private $exec;
    private $result;

    public function __construct()
    {
        $this->obj = new DBConnection();
        $this->connection = $this->obj->connect();
    }

    public function findAll(): ?array
    {
        $this->query = "SELECT * FROM user;";
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
        $this->query = "SELECT * FROM user WHERE $criteria = '$value';";
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

    public function create(string $email, string $password, string $datetime): ?bool
    {
        $this->query = "INSERT INTO user(email, password, created_at) VALUES ('$email', '$password', '$datetime');";
        if ($this->exec = $this->connection->query($this->query))
        {
            $this->result = true;
        }
        return $this->result;
    }
}
