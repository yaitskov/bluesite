<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'prname'); ?>
		<?php echo $form->textField($model,'prname',array('maxlength'=>128)); ?>
		<?php echo $form->error($model,'prname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo CHtml::activeTextArea($model,'description',array('rows'=>10, 'cols'=>70)); ?>
		<p class="hint">You may use
            <a target="_blank" href="http://daringfireball.net/projects/markdown/syntax">
               Markdown syntax</a>.</p>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->