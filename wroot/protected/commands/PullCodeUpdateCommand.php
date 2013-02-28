<?php

class PullCodeUpdateCommand extends CConsoleCommand {
    public function getHelp() {
        echo "Pulls commits from gitlab and checkout them\nand applies SQL update scripts\n";
    }

    public function run($args) {
        $this->pullCommits();
        $this->applySqlUpdates();
    }

    protected function pullCommits() {
        // git repo root
        $root = dirname(dirname(dirname(__FILE__)));
        $repo = Yii::app()->params['pull-repo'];
        $branch = Yii::app()->params['pull-branch'];
        echo "root $root\n";
        $cmd = "pushd $root ; git pull $repo $branch; popd ; ";
        echo "execute: $cmd\n";
        shell_exec($cmd);
    }

    protected function getPatchId($path) {
        return  (int)preg_replace('/.sql$/', '', basename($path));
    }

    protected function filterAndSortPatches($version) {
        echo "current database version $version\n";
        $pattern = dirname(__FILE__).'/data/updates/*.sql';
        $files = glob($pattern);
        $toBeApplied = array_filter($files,
            function ($path) use($version) {               
                return  $this->getPatchId($path) > $version;
            });
        
        usort($toBeApplied, function($a, $b) { // by desc
                return $this->getPatchId($a) > $this->getPatchId($b);
            });
        echo "applies patches " . CVarDumper::dumpAsString($toBeApplied) . "\n";
        return $toBeApplied;
    }

    protected function applyPatches($toBeApplied) {
        $connection = Yii::app()->db;
        foreach ($toBeApplied as $patch) {
            $sqls = file_get_contents($sqlFile);
            foreach(explode(';',$sqls) as $sql)
            {  //todo: i don't know how check that sql was applied without errors.
                if(trim($sql)!=='') {
                    $connection->createCommand($sql)->execute();                    
                }
            }  // there is flag required for marking schema as damaged and there is required human intervention
            echo "patch $patch is ok\n";
            $meta->schema_version = $this->getPatchId($patch);
            if (!$meta->save()) {
                throw new Exception("failed to update schema version upto "
                    . $meta->schema_version);
            }
        }        
    }
    
    
    protected function applySqlUpdates() {
        $meta = Meta::model()->find(); // current version
        $version = $meta->schema_version;
        $toBeApplied = $this->filterAndSortPatched($version);
        $this->applyPatches($toBeApplied);
    }
}

?>