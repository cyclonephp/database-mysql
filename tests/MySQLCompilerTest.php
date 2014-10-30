<?php
namespace cyclonephp\database\mysql;

class MySQLCompilerTest extends \PHPUnit_Framework_TestCase {
    
    public function testEscapeIdentifier() {
        $conn = $this->getMock('mysqli');
        $subject = new MySQLCompiler($conn);
        $this->assertEquals("`id`", $subject->escapeIdentifier('id'));
    }
    
}
