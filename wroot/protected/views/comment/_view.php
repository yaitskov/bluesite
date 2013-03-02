<?php
$deleteJS = <<<DEL
hookCommentHandlers($('#comments'));
DEL;
Yii::app()->getClientScript()->registerScript('delete', $deleteJS);
?>
<div class="comment" id="c<?php echo $data->id; ?>">

	<div class="author">
		<?php echo $data->author->username; ?>
	</div>

	<div class="time">
        <?php if ($data->canDelete(Yii::app()->user)): ?>
	   	<?php echo CHtml::link('Delete',array('comment/delete','id'=>$data->id),array('class'=>'delete')); ?> |
        <?php endif; ?>
		<?php echo date('F j, Y \a\t h:i a',$data->created); ?>
	</div>

	<div class="content">
		<?php echo nl2br(CHtml::encode($data->content)); ?>
	</div>

</div><!-- comment -->