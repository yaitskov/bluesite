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
        $cmd = "pushd $root || exit 1; git pull $repo $branch || exit 2 ; popd || exit 3; ";
        echo "execute: $cmd\n";
        ExtProcess::run($cmd);
    }

    public function getPatchId($path) {
        return  (int)preg_replace('/.sql$/', '', basename($path));
    }

    protected function filterAndSortPatches($version) {
        echo "current database version $version\n";
        $pattern = dirname(dirname(__FILE__)).'/data/updates/*.sql';
        $files = glob($pattern);
	$ci = $this;
        $toBeApplied = array_filter($files,
            function ($path) use($version, $ci) {
                $id = $ci->getPatchId($path);
                return $id > $version;
            });
        
        usort($toBeApplied, function($a, $b) use($ci) { // by desc
                return $ci->getPatchId($a) > $ci->getPatchId($b);
            });
        if ($toBeApplied) {
            echo "applies patches " . join($toBeApplied, ", ") . "\n";
        } else {
            echo "nothing to apply\n";
        }
        return $toBeApplied;
    }

    protected function applyPatches($toBeApplied, $meta) {
        $connection = Yii::app()->db;
        foreach ($toBeApplied as $patch) {
            $sqls = file_get_contents($patch);
            echo "body of $patch is got\n";
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
        $toBeApplied = $this->filterAndSortPatches($version);
        if ($toBeApplied) {
            $dumper = new DumpDbCommand('dumper', new CConsoleCommandRunner()); 
            $fullname =  preg_replace('/.sql$/', '-before-update.sql', $dumper->getPathToFile());
            $dumper->dump($fullname);
            $this->applyPatches($toBeApplied, $meta);
        }
    }
}

?>