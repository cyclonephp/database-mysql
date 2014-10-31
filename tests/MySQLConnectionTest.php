<?php
namespace cyclonephp\database\mysql;

class MySQLConnectionTest extends \PHPUnit_Framework_TestCase {
    
    public function setUp() {
        if (!extension_loaded('mysqli')) {
            $this->markTestSkipped('mysqli extension is not installed');
        }
    }
    
    /**
     * @expectedException \cyclonephp\database\ConnectionException
     */
    public function testConnectionFailure() {
        MySQLConnection::forConnectionInfo('whateverhost', 'somebody', 'top secret', 'foo')
            ->connect();
    }
    
    public function testCloseConnectionFailure() {
        $mysqli = $this->getMock('mysqli');
        $mysqli->expects($this->once())->method('close')->willReturn(true);
        MySQLConnection::forExistingConnection($mysqli)->disconnect();
    }
    
    private function mysqliExpectingAutoCommit($autocommit, $success = true) {
        $rval = $this->getMock('mysqli');
        $rval->expects($this->once())
                ->method('autocommit')
                ->with($this->equalTo($autocommit))
                ->willReturn($success);
        return $rval;
    }
    
    public function testStartTransactionSuccess() {
        $mysqli = $this->mysqliExpectingAutoCommit(false);
        $subject = MySQLConnection::forExistingConnection($mysqli);
        $subject->startTransaction();
    }
    
    /**
     * @expectedException \cyclonephp\database\DatabaseException
     * @expectedExceptionMessage  connection is already in transaction
     */
    public function testNestedTransactionFailure() {
        $mysqli = $this->mysqliExpectingAutoCommit(false);
        $subject = MySQLConnection::forExistingConnection($mysqli);
        $subject->startTransaction();
        $subject->startTransaction();
    }
    
    /**
     * @expectedException \cyclonephp\database\DatabaseException
     * @expectedExceptionMessage failed to change autocommit mode:
     */
    public function testStartTransactionFailure() {
        $mysqli = $this->mysqliExpectingAutoCommit(false, false);
        $subject = MySQLConnection::forExistingConnection($mysqli);
        $subject->startTransaction();
    }
    
    
}