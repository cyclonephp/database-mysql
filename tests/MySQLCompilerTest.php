<?php
namespace cyclonephp\database\mysql;

use cyclonephp\database\DB;

class MySQLCompilerTest extends \PHPUnit_Framework_TestCase {
    
    public function testEscapeParameter() {
        $conn = $this->getMock('mysqli');
        $conn->expects($this->once())
                ->method('real_escape_string')
                ->with($this->equalTo('param'))
                ->willReturn('escapedparam');
        $this->assertEquals('escapedparam', (new MySQLCompiler($conn))->escapeParameter('param'));
    }
    
}
