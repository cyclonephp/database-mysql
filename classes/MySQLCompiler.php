<?php
namespace cyclonephp\database\mysql;

use cyclonephp\database\AbstractCompiler;

class MySQLCompiler extends AbstractCompiler {
    
    /**
     * @var \MySQLi
     */
    private $connection;
    
    public function __construct(\MySQLi $connection) {
        $this->connection = $connection;
    }

    public function escapeIdentifier($identifier) {
        return "`$identifier`";
    }

    public function escapeParameter($param) {
        return "'" . $this->connection->real_escape_string($param) . "'";
    }

    
}