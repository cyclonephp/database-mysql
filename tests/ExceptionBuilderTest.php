<?php
namespace cyclonephp\database\mysql;

use cyclonephp\database\ConstraintException;

class ExceptionBuilderTest extends \PHPUnit_Framework_TestCase {
    
    public function providerConstraintExceptions() {
        return [
            [
                "Column 'name' cannot be null",
                1048,
                ConstraintException::builder()
                ->constraintType(ConstraintException::NOTNULL)
                ->column('name')
            ],
            [
                "Duplicate entry 'u@e' for key 'email'",
                1062,
                ConstraintException::builder()
                ->constraintType(ConstraintException::UNIQUE)
                ->column('email')
            ],
            [
                'Cannot add or update a child row: a foreign key constraint fails '
                    . '(`simpledb`.`t_posts`, CONSTRAINT `t_posts_ibfk_1` FOREIGN KEY (`user_fk`)'
                    . ' REFERENCES `cy_user` (`id`))',
                1452,
                ConstraintException::builder()
                ->constraintType(ConstraintException::FOREIGNKEY)
                ->constraintName('t_posts_ibfk_1')
                ->column('user_fk')
            ]
        ];
    }
    
    
    /**
     * @dataProvider providerConstraintExceptions
     */
    public function testConstraintExceptions($errorMessage, $errorCode, $expected) {
        $expected->errorMessage($errorMessage)->errorCode($errorCode);
        $subject = new ExceptionBuilder($errorMessage, $errorCode);
        $actual = $subject->buildException();
        $this->assertEquals($expected->build(), $actual);
    }
}