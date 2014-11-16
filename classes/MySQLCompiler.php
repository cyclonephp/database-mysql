<?php
namespace cyclonephp\database\mysql;

use cyclonephp\database\AbstractCompiler;

class MySQLCompiler extends AbstractCompiler {
    
    /**
     * @var MySQLConnection
     */
    private $connection;
    
    public function __construct(MySQLConnection $connection) {
        $this->connection = $connection;
    }

    public function escapeIdentifier($identifier) {
        return "`$identifier`";
    }

    public function escapeParameter($param) {
        return "'" . $this->connection->getConnection()->real_escape_string($param) . "'";
    }

    
}