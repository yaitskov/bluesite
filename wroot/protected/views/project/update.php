<?php
$this->breadcrumbs=array(
	$model->prname => $model->url,
	'Update',
);
?>

<h1>Update <i><?php echo CHtml::encode($model->prname); ?></i></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>