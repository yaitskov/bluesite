<?php

class ExtProcess extends CComponent {
    /**
     * @return stdout
     * @throws if exit code is not zero
     */
    public static function run($cmd) {
        $fullcmd = "bash -c '$cmd'";
        $out = array();
        exec($fullcmd, $out, $ecode);        
        if ($ecode) {
            throw new ProcessExitCodeException($ecode, join($out, "\n"));
        }
        return join($out, "\n");
    }
}

?>