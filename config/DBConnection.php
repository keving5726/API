<?php

namespace Config;

class DBConnection
{
    private $host;
    private $username;
    private $passwd;
    private $dbname;
    private $port;
    private $connection;

    protected function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->passwd = $_ENV['DB_PASSWORD'];
        $this->dbname = $_ENV['DB_DATABASE'];
        $this->port = $_ENV['DB_PORT'];
    }

    protected function connect(): object
    {
        $this->connection = new \mysqli("$this->host", "$this->username", "$this->passwd", "$this->dbname", $this->port);
        if ($this->connection->connect_errno)
        {
            die ('Connect Error (' . $this->connection->connect_errno . ') '
                . $this->connection->connect_error);
        }
        return $this->connection;
    }
}
