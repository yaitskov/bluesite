<?php

// this contains the application parameters that can be maintained via GUI
return array(
    // max uploadable file size in bytes
    'max-uploadable-file' => 250000,
    // path to git control version system
    'gitpath' => '/usr/bin/git',
    // project repositories path
    'project-repos-path' => '/tmp/project-repos-path',
    // git branch to pull
    'pull-branch' => 'master',
    // git remote repository to pull
    'pull-repo' => 'hub',    
    // max number db backups. if a backup is created each night and the value is 3 then 3 files will
    'numbackups' => 3,
    //
    'backup-folder' => '/tmp/bluebackup',
	// number of projects displayed per page
	'projectsPerPage'=>10,
    //
    'company' => 'Horns and Hoofs',
	// this is displayed in the header section
	'title'=>'My Yii Blog',
	// this is used in error pages
	'adminEmail'=>'webmaster@example.com',
	// number of posts displayed per page
	'postsPerPage'=>10,
	// maximum number of comments that can be displayed in recent comments portlet
	'recentCommentCount'=>10,
	// maximum number of tags that can be displayed in tag cloud portlet
	'tagCloudCount'=>20,
	// whether post comments need to be approved before published
	'commentNeedApproval'=>true,
	// the copyright information displayed in the footer section
	'copyrightInfo'=>'Copyright &copy; 2009 by My Company.',
);
