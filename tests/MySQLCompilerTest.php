<?php
namespace cyclonephp\database\mysql;

class MySQLCompilerTest extends \PHPUnit_Framework_TestCase {
    
    public function setUp() {
        if (!extension_loaded('mysqli')) {
            $this->markTestSkipped('mysqli extension is not installed');
        }
    }
    
    public function testEscapeIdentifier() {
        $conn = $this->getMockBuilder('cyclonephp\\database\\mysql\\MySQLConnection', [])
                ->disableOriginalConstructor()
                ->getMock();
        $subject = new MySQLCompiler($conn);
        $this->assertEquals("`id`", $subject->escapeIdentifier('id'));
    }
    
}
