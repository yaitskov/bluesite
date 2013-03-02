<?php

/**
 *  This command just only apply all patches to base schema.
 */
class ApplySqlPatchesCommand extends CConsoleCommand {
    public function getHelp() {
        echo "Apply all patches to base schema\nFor initial deploy only.\n";
    }

    public function run($args) {
        $toBeApplied = $this->filterAndSortPatches();
        $this->applyPatches($toBeApplied);
    }

    protected function applyPatch($connection, $patch) {
        $sqls = file_get_contents($patch);
        echo "body of $patch is got\n";
        foreach(explode(';',$sqls) as $sql)
        {  //todo: i don't know how check that sql was applied without errors.
            if(trim($sql)!=='') {
                $connection->createCommand($sql)->execute();                    
            }
        }  // there is flag required for marking schema as damaged and there is required human intervention
    }
    
    protected function applyPatches($toBeApplied) {
        $connection = Yii::app()->db;
        foreach ($toBeApplied as $patch) {
            $this->applyPatch($connection, $patch);
        }        
    }

    public function getPatchId($path) {
        return  (int)preg_replace('/.sql$/', '', basename($path));
    }

    protected function filterAndSortPatches() {
        $pattern = dirname(dirname(__FILE__)).'/data/updates/*.sql';
        $toBeApplied = glob($pattern);
        $ci = $this;
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
}