<?php
$this->breadcrumbs=array(
	$model->prname,
);
$this->pageTitle=$model->prname;
?>
<?php if(!Yii::app()->user->isGuest): ?>
<div class="secondmenu">
 <?php $this->widget('zii.widgets.CMenu',array(
            'items'=>array(
				array('label'=>'Follow',
                    'visible' => $model->canFollow(Yii::app()->user->id)
                    and !$model->doesFollow(Yii::app()->user->id),
                    'url'=> array('project/follow', 'id' => $model->id)),                
				array('label'=>'Forget',
                    'visible' => $model->canFollow(Yii::app()->user->id)
                    and $model->doesFollow(Yii::app()->user->id),
                    'url'=> array('project/forget', 'id' => $model->id)),                
				array('label'=>'Edit',
                    'visible' => $model->canChange(Yii::app()->user->id),
                    'url'=> array('project/update', 'id' => $model->id)),
				array('label'=>'Drop',
                    'visible' => $model->canChange(Yii::app()->user->id),                    
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


