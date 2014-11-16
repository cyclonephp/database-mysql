<?php

namespace cyclonephp\database\mysql;

use cyclonephp\database\AbstractQueryResult;

class MySQLQueryResult extends AbstractQueryResult {
    
    /**
     *
     * @var \MySQLi_Result
     */
    private $result;
    
    function __construct(\MySQLi_Result $result) {
        $this->result = $result;
    }

    public function count($mode = 'COUNT_NORMAL') {
        return $this->result->num_rows;
    }

    public function current() {
        return $this->currentRow;
    }

    public function next() {
        $this->currentRow = $this->result->fetch_assoc();
        ++$this->index;
    }
    
    public function seek($pos) {
        $this->result->data_seek($pos);
        $this->index = $pos;
    }

    public function rewind() {
        $this->result->data_seek(0);
        $this->index = -1;
        $this->next();
    }

    public function valid() {
        return $this->currentRow != null;
    }
    
    public function toArray() {
        $rval = [];
        foreach ($this as $k => $v) {
            $rval[$k] = $v;
        }
        return $rval;
    }
    
    public function  __destruct() {
        $this->result->free();
    }

}
