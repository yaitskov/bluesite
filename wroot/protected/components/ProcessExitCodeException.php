<?php

class ProcessExitCodeException extends Exception {
    private $ecode;
    public function __construct($ecode, $out) {
        parent::__construct("exit code $ecode and out: out");
        $this->ecode = $ecode;
    }    
}    
?>