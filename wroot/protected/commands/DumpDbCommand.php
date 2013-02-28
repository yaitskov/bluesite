<?php

/**
 * Create a backup of the database.
 */
class DumpDbCommand extends CConsoleCommand {

    public function getHelp() {
        $f = Yii::app()->params['backup-folder'];
        echo "makes backup of db and puts it to folder '$f'\n";
    }

    protected function getPathToFile() {
        $backfolder = Yii::app()->params['backup-folder'];
        if (!file_exists($backfolder)) {
            if (mkdir($backfolder, 0777, true)) {
                echo "folder '$backfolder' is created\n";
            }
        }
        if (!file_exists($backfolder)) {
            throw new Exception("Folder $backfolder does not exist");
        }
        if (!is_dir($backfolder)) {
            throw new Exception("Path $backfolder is not a folder");            
        }
        $backfile = 'dump-' . date('Y-m-d_H:i:s');
        $fullname = $backfolder . '/' . $backfile . '.sql';
        if (file_exists($fullname)) {
            throw new Exception("file $fullname already exists");
        }
        return $fullname;
    }

    protected function dump($fullname) {
        $cmd = "mysqldump -u " .
            Yii::app()->params['dbuser'] . ' -p' .
            Yii::app()->params['dbpass'] . ' blue > ' . $fullname;
        echo "execute: '$cmd'\n";
        $result = shell_exec($cmd);// . $fullname);
        // null even if it's okay. exit code is hard to get.
        // if ($result === null) {
        /*     throw new Exception("failed to create the archive"); */
        /* }                 */
    }

    protected function removeOldBackups($fullname) {
        echo "Remove old backups if ones exists. Max backups is "
            . Yii::app()->params['numbackups'] . "\n";
        $maxBackups = (int) Yii::app()->params['numbackups'];
        if ($maxBackups < 3) {
            echo("max backups is too small. override to 3");
            $maxBackups = 3;
        }
        $dir = dirname($fullname);
        $files = glob($dir . '/*.sql');
        usort($files, function($a, $b) { // by desc
                return filemtime($b) > filemtime($a);
            });
        $toDestroy = array_slice($files, $maxBackups);
        foreach ($toDestroy as $tooOldFile) {
            echo "remove file '$tooOldFile'\n";
            unlink($tooOldFile);
        }
    }
    
    public function run($args) {
        // 'numbackups' => 3,
        $fullname = $this->getPathToFile();
        $this->dump($fullname);
        $this->removeOldBackups($fullname);       
    }
}
?>