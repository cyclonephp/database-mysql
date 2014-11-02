<?php
namespace cyclonephp\database\mysql;

class MySQLExecutorTest extends \PHPUnit_Framework_TestCase {
    
    private function createSubject(\MySQLi $mysqli) {
        return new MySQLExecutor(MySQLConnection::forExistingConnection($mysqli));
    }
    
    public function testExecDelete() {
        $this->markTestSkipped('no property assignment so we need a hand-written mysqli mock for this');
        $stmt = 'delete from whatever';
        $obj = new \stdclass;
        $obj->affected_rows = 10;
        $mysqli = $this->getMockBuilder('mysqli')->setProxyTarget($obj)->setMethods(['query'])->getMock();
        // $mysqli = $this->getMock('mysqli');
        $mysqli->expects($this->once())
                ->method('query')
                ->with($this->equalTo($stmt))
                ->willReturn(true);
        //$mysqli->affected_rows = 10;
        $subject = $this->createSubject($mysqli);
        $this->assertEquals(10, $subject->execDelete($stmt));
    }
    
}