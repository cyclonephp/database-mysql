<?php
namespace cyclonephp\database\mysql;

use cyclonephp\database\DB;

class MySQLExecutorTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @var MySQLExecutor;
     */
    private $subject;
    
    /**
     *
     * @var MySQLCompiler
     */
    private $compiler;
    
    public function setUp() {
        $testSchema = file_get_contents(__DIR__ . '/test-schema.sql');
        $conn = MySQLConnection::forConnectionInfo('localhost', 'cyclonephp', 'cyclonephp', 'cyclonephp', 'utf8');
        $mysqli = $conn->getConnection();
        $mysqli->multi_query($testSchema);
        while($mysqli->more_results()) {
            $mysqli->next_result();
        }
        $this->subject = new MySQLExecutor($conn);
        $this->compiler = new MySQLCompiler($conn);
    }
    
    private function insertTestUsers() {
        $names = array('user1', 'user2');
        $insertStmt = DB::insert('cy_user')->columns(['name', 'email'])
                ->values(['user1', 'user1@example.org'])
                ->values(['user2', 'user2@example.org']);
        $dml = $this->compiler->compileInsert($insertStmt);
        $rowCount = $this->subject->execInsert($dml);
        $this->assertEquals(2, $rowCount);
    }
    
    private function execDefaultQuery() {
        $this->insertTestUsers();
        $query = DB::select('name', 'email')->from('cy_user');
        $sql = $this->compiler->compileQuery($query);
        $actual = $this->subject->execQuery($sql);
        $this->assertInstanceOf('cyclonephp\\database\\mysql\\MySQLQueryResult', $actual);
        return $actual;
    }
    
    public function testExecSelect() {
        $actual = $this->execDefaultQuery();
        $this->assertEquals(2, count($actual));
        $expectedResult = [
            ['name' => 'user1', 'email' => 'user1@example.org'],
            ['name' => 'user2', 'email' => 'user2@example.org']
        ];
        $this->assertEquals($expectedResult, $actual->toArray());
    }
    
    public function testExecSelectWithIndex() {
        $actual = $this->execDefaultQuery()->indexBy('name');
        $expectedResult = [
            'user1' => ['name' => 'user1', 'email' => 'user1@example.org'],
            'user2' => ['name' => 'user2', 'email' => 'user2@example.org']
        ];
        $this->assertEquals($expectedResult, $actual->toArray());
    }
    
    public function testExecInsert() {
        $actual = $this->subject->execInsert("INSERT INTO cy_user (`name`) VALUES ('hello');");
        $this->assertEquals(1, $actual);
    }
    
    
}