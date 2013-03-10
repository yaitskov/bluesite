<?php

/**
 * Control repositories 
 */
class RepoStorage extends CApplicationComponent {

    /**       
     * @var path to folder with repositories
     */
    public $path;

    /**
     *  Creates path if not exists
     */
    public function init() {
        parent::init();
        //$this->path = Yii::app()->params['project-repos-path'];
        if (!file_exists($this->path)) {
            if (!@mkdir($this->path, 0777, true)) {
                throw new Exception("Failed create path '" . $this->path . "'");
            }
        }
    }

    /**
     *  Creates new repository
     */
    public function create($project_id) {
        $project_path = $this->path . '/' . $project_id;
        if (file_exists($project_path)) {
            throw new Exception("repository already exists");
        }
        if(!mkdir($project_path)) {
            throw new Exception("failed to create repository '" . $project_path . "'");
        }
    }

    /**
     * Drops repository
     */
    public function delete($project_id) {
        $project_path = $this->path . '/' . $project_id;
        $this->destroy_dir($project_path);
    }
    
    public function destroy_dir($dir) { 
        if (!is_dir($dir) || is_link($dir))
            return unlink($dir); 
        foreach (scandir($dir) as $file) { 
            if ($file == '.' || $file == '..') continue; 
            if (!destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) { 
                chmod($dir . DIRECTORY_SEPARATOR . $file, 0777); 
                if (!destroy_dir($dir . DIRECTORY_SEPARATOR . $file))
                    return false; 
            }; 
        } 
        return rmdir($dir); 
    }

    public function persistFile($project_id, $file_id, $file) {
        $repo_path = $this->path . '/' . $project_id;
        if (!file_exists($repo_path)) {
            Yii::log('create folder '. $repo_path, 'info');
            mkdir($repo_path);
        }        
        $file_path = $repo_path . '/' . $file_id;
        try {
            $file->saveAs($file_path);
        } catch (Exception $e) {
            throw new Exception("Failed to save uploaded file to $file_path", 0, $e);
        }
    }

    public function dropFile($project_id, $file_id) {
        $file_path = $this->path . '/' . $project_id . '/' . $file_id;
        unlink($file_path);         
    }
}

?>