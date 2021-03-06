<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Popular', 'url'=>array('project/index')),
				array('label'=>'Newest', 'url'=>array('project/newest')),                
				array('label'=>'My projects',
                    'url'=>array('project/myProjects'),
                    'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Login', 'url'=>array('site/login'),
                    'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Register', 'url'=>array('site/register'),
                    'visible'=>Yii::app()->user->isGuest),                
				array('label'=>'Logout ('.Yii::app()->user->name.')',
                    'url'=>array('site/logout'),
                    'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->

	<?php echo $content; ?>

	<div id="footer">
        Copyright &copy; <?php echo date('Y'); ?> by 
        <?php echo Yii::app()->params['company']; ?>.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->

<div id='errbox'></div>
<div class='templates'>
<div class='questionbox'>
  <div class='questiontext'></div>
  <div>
      <a id='btnyes'>yes</a>
      <a id='btnno'>no</a>
  </div>
</div>
</div>
</body>
</html>