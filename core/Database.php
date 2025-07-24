<?php

namespace Application\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;

    public function __construct($config)
    {
        try{
            $this->connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch(PDOException $e)
        {
            die("Database connection Error: " . $e->getMessage());
        }
    }


   public static function getInstance($config = null)
{
    if (self::$instance === null) {
        if ($config === null) {
            throw new \Exception('database configuration required');
        }
        self::$instance = new self($config);
    }
    return self::$instance;
}


 public function getConnection()
{
    return $this->connection;
}

}