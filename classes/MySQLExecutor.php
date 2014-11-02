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
        return $this->execDMLStatement($deleteStmt);
    }

    public function execInsert($insertStmt) {
        return $this->execDMLStatement($deleteStmt);
    }

    public function execQuery($queryString) {
        
    }
    
    private function execDMLStatement($stmt) {
        $mysqli = $this->connection->getConnection();
        $mysqli->query($stmt);
        return $mysqli->affected_rows;
    }

    public function execUpdate($updateStmt) {
        return $this->execDMLStatement($deleteStmt);
    }

    
}