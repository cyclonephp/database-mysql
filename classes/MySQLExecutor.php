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
        return $this->execDMLStatement($insertStmt);
    }

    public function execQuery($queryString) {
        $conn = $this->connection->getConnection();
        $result = $conn->query($queryString);
        if ($result === false)
            throw (new ExceptionBuilder($conn->error, $conn->errno))->buildException();
        return new MySQLQueryResult($result);
    }
    
    
    
    private function execDMLStatement($stmt) {
        $mysqli = $this->connection->getConnection();
        $result = $mysqli->query($stmt);
        if ($result === false)
            throw (new ExceptionBuilder($mysqli->error, $mysqli->errno))->buildException();
        
        return $mysqli->affected_rows;
    }

    public function execUpdate($updateStmt) {
        return $this->execDMLStatement($deleteStmt);
    }

    
}