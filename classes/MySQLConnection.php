<?php

namespace cyclonephp\database\mysql;

use cyclonephp\database\Connection;
use cyclonephp\database\ConnectionException;
use cyclonephp\database\DatabaseException;

class MySQLConnection implements Connection {

    /**
     * @param \MySQLi $connection
     * @return MySQLConnection
     */
    public static function forExistingConnection(\MySQLi $connection) {
        $connProvider = function() use ($connection) {
            return $connection;
        };
        return new MySQLConnection($connProvider);
    }

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @param string $charset
     * @param int $port
     * @param string $socket
     * @return MySQLConnection
     */
    public static function forConnectionInfo($host, $username, $password, $database, $charset = null, $port = null, $socket = null) {
        $connProvider = function() use ($host,
                $username,
                $password,
                $database,
                $charset,
                $port,
                $socket) {
            if ($port === null) {
                $port = ini_get('mysqli.default_port');
            }
            if ($socket === null) {
                $socket = ini_get('mysqli.default_socket');
            }
            $conn = @new \MySQLi($host, $username, $password, $database, $port, $socket);
            if (mysqli_connect_errno())
                throw new ConnectionException('failed to connect: ' . mysqli_connect_error());
            if ($charset !== null) {
                $conn->set_charset($charset);
            }
            return $conn;
        };
        return new MySQLConnection($connProvider);
    }

    /**
     * @var callable
     */
    private $connectionProvider;

    /**
     * @var \MySQLi
     */
    private $connection;

    /**
     * @var boolean
     */
    private $inTransaction = false;

    /**
     * @param callable $connectionProvider a callable returning a MySQLi instance
     *  to be used by this connection
     */
    public function __construct(callable $connectionProvider) {
        $this->connectionProvider = $connectionProvider;
    }

    /**
     * @return MySQLConnection
     */
    public function commit() {
        if ( ! $this->inTransaction)
            throw new DatabaseException('connection is not in transaction');
        
        if ( ! $this->getConnection()->commit())
            throw $this->exceptionForConnectionError('failed to commit transaction: ');
        
        $this->finishTransaction();
        return $this;
    }
    
    private function finishTransaction() {
        $this->setAutocommit(true);
        $this->inTransaction = false;
    }

    /**
     * @return MySQLConnection
     */
    public function connect() {
        $this->getConnection();
        return $this;
    }

    /**
     * @return MySQLConnection
     */
    public function disconnect() {
        if ( ! $this->getConnection()->close())
            throw $this->exceptionForConnectionError('failed to close connection: ');
        
        return $this;
    }
    
    private function exceptionForConnectionError($messagePrefix) {
        $conn = $this->getConnection();
        return new DatabaseException($messagePrefix . $conn->error, $conn->errno, null);
    }

    /**
     * @return MySQLConnection
     */
    public function rollback() {
        if ( ! $this->inTransaction)
            throw new DatabaseException('connection is not in transaction');
        
        if ( ! $this->getConnection()->rollback())
            throw $this->exceptionForConnectionError('failed to rollback: ' );
        
        $this->finishTransaction();
        return $this;
    }
    
    private function setAutocommit($autocommit) {
        if ( ! $this->getConnection()->autocommit($autocommit))
            throw $this->exceptionForConnectionError('failed to change autocommit mode: ');
    }

    /**
     * @return MySQLConnection
     */
    public function startTransaction() {
        if ($this->inTransaction)
            throw new DatabaseException('connection is already in transaction');
        
        $this->setAutocommit(false);
        $this->inTransaction = true;
        return $this;
    }

    /**
     * @return \MySQLi
     */
    public function getConnection() {
        if ($this->connection === null) {
            $connProvider = $this->connectionProvider;
            $this->connection = $connProvider();
        }
        return $this->connection;
    }

}
