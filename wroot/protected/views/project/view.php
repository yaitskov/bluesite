<?php

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/app.js');

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

<div id="comments">
    <h3 id='nocomments' <? if($model->ncomments>0): ?>style='display: none;'<?php endif; ?>>No comments</h3>
    <h3 id='onecomment' <? if($model->ncomments!=1): ?>style='display: none;'<?php endif; ?>>One comment</h3>
    <h3 id='ncomments' <? if($model->ncomments<2): ?>style='display: none;'<?php endif; ?>>
          <span class="numx"><?php echo $model->ncomments; ?></span> comments</h3>
    <div class="comment-bodies">
    <?php foreach($model->comments as $comment): ?>
    <?php $this->renderPartial('/comment/_view',array('data'=>$comment)); ?>
    <?php endforeach; ?>  
    </div>

    <?php if($model->canComment(Yii::app()->user)): ?>
    <h3>Leave a Comment</h3>
    <?php $this->renderPartial('/comment/_form',array(
			'model'=>$newComment,
                )); ?>
    <?php else: ?>
     <h4>Unregistered users cannot leave a comment</h4>
    <?php endif; ?>
</div>


