<?php

class MarkSchemaValidCommand extends CConsoleCommand {
    public function getHelp() {
        echo <<<EOF
Set tbl_meta.schema_consistent to 1.
This is just for convience.
The script takes number of last patch.

Often script has a syntactic errors and it doesn't change any 
and crashes with error. So it's required to set flag.
EOF;
    }

    protected function getPatch($args) {
        if (count($args) != 1) {
            echo "expected patch number\n";
            exit(1);
        }
        if (preg_match('/^[0-9]+$/', $args[0])) {
            echo "first argument is not an integer\n";
            exit(1);
        }
        return (int)$args[0];
    }

    public function run($args) {
        //$patch = $this->getPatch($args);
        $meta = Meta::model()->find(); // current version
        if ($meta->schema_consistent) {
            echo "Schema already consistent\n";
        } else {
            echo "Current version: " . $meta->schema_version . "\n";
            $meta->schema_consistent = 1;
            $meta->save();
        }
    }
}

?>