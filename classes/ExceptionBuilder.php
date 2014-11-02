<?php
namespace cyclonephp\database\mysql;

use cyclonephp\database\ConstraintException;


class ExceptionBuilder {
    
    const MISSING_RELATION = 1146;

    const MISSING_COLUMN = 1054;

    const MISSING_FUNCTION = 1305;

    const NOTNULL_CONSTRAINT = 1048;

    const UNIQUE_CONSTRAINT  = 1062;

    const FOREIGNKEY_CONSTRAINT = 1452;
    
    private $errorMessage;
    
    private $errorCode;
    
    private $exceptionBuilder;
    
    function __construct($errorMessage, $errorCode) {
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;
        $this->exceptionBuilder = ConstraintException::builder()
                ->errorMessage($errorMessage)
                ->errorCode($errorCode);
    }
    
    /**
     * @return \cyclonephp\database\DatabaseException
     */
    public function buildException() {
        switch ($this->errorCode) {
            case self::NOTNULL_CONSTRAINT:
                $this->buildNotNullConstraintException();
                break;
            case self::UNIQUE_CONSTRAINT:
                $this->buildUniqueConstraintException();
                break;
            case self::FOREIGNKEY_CONSTRAINT:
                $this->buildForeignKeyConstraintException();
                break;
        }
        return $this->exceptionBuilder->build();
    }
    
    private function buildNotNullConstraintException() {
        $this->exceptionBuilder->constraintType(ConstraintException::NOTNULL);
        $substr = substr($this->errorMessage, strpos($this->errorMessage, "'") + 1);
        $this->exceptionBuilder->column(substr($substr, 0, strpos($substr, "'")));
    }
    
    private function buildUniqueConstraintException() {
        $this->exceptionBuilder->constraintType(ConstraintException::UNIQUE);
        $parts = explode("'", $this->errorMessage);
        $this->exceptionBuilder->column($parts[3]);
    }
    
    private function buildForeignKeyConstraintException() {
        $this->exceptionBuilder->constraintType(ConstraintException::FOREIGNKEY);
        $parts = explode('`', $this->errorMessage);
        $this->exceptionBuilder->constraintName($parts[5]);
        $this->exceptionBuilder->column($parts[7]);
    }
    
}