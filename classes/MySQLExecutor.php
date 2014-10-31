<?php
namespace cyclonephp\database\mysql;

use cyclonephp\database\Executor;

class MySQLExecutor implements Executor {
    
    /**
     * @var MySQLConnection
     */
    private $connection;
    
    public function __construct(MySQLConnection $connection) {
        $this->connection = $connection;
    }

    
    public function execDelete($deleteStmt) {
        $mysqli = $this->connection->getConnection();
        $mysqli->query($deleteStmt);
        return $mysqli->affected_rows;
    }

    public function execInsert($insertStmt) {
        
    }

    public function execQuery($queryString) {
        
    }

    public function execUpdate($updateStmt) {
        
    }

    
}