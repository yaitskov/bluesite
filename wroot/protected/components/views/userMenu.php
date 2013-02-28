<ul>
<?php if (Project::canCreate(Yii::app()->user)): ?>
	<li><?php echo CHtml::link('Create New Project',array('project/create')); ?></li>
<?php endif; ?>
</ul>