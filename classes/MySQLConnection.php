<?php
namespace cyclonephp\database\mysql;

use cyclonephp\database\Connection;

class MySQLConnection implements Connection {
    
    /**
     *
     * @var \MySQLi
     */
    private $connection;
    
    /**
     * @var boolean
     */
    private $inTransaction = false;
    
    public function __construct(\MySQLi $connection) {
        $this->connection = $connection;
    }

    public function commit() {
        
    }

    public function connect() {
        
    }

    public function disconnect() {
        
    }

    public function rollback() {
        
    }

    public function startTransaction() {
        
    }

    
}