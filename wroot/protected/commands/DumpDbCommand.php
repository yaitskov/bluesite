<?php

/**
 * Create a backup of the database.
 */
class DumpDbCommand extends CConsoleCommand {
    
    public function run($args) {
        // 'numbackups' => 3,
        
        echo "max backups is " . Yii::app()->params['numbackups'] . "\n";
    }
}
?>