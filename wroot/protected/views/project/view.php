<?php
$this->breadcrumbs=array(
	$model->prname,
);
$this->pageTitle=$model->prname;
?>
<?php if($model->canChange(Yii::app()->user->id)): ?>
<div class="secondmenu">
 <?php $this->widget('zii.widgets.CMenu',array(
            'items'=>array(
				array('label'=>'Edit',
                    'url'=> array('project/update', 'id' => $model->id)),
				array('label'=>'Drop',
                    'url'=> array('project/delete', 'id' => $model->id)),                
            ))); ?>

</div>
<?php endif; ?>


<h1>Project <?php echo CHtml::encode($model->prname); ?></h1>
<div class="project">
    <div class="description">
        <pre><?php echo $model->description; ?>
</pre>
    </div>
</div>


