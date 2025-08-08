<?php

class Connection {
    private $hostname = "localhost";
    private $username = "admin";
    private $password = "admin";
    private $database = "News";
    public $connection;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->connection = new mysqli(
            $this->hostname,
            $this->username,
            $this->password,
            $this->database
        );

        if ($this->connection->connect_errno) {
            die("Connection error: " . $this->connection->connect_error);
        }
}
}