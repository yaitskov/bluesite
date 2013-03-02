<div class="form">
     <?php  
$submitJS = <<<EOS
function submitAjax(form, data, hasError) {
    if (!hasError){
        // No errors! Do your post and stuff
        $.post(form.attr('action'), form.serialize(), function(res){
            // Do stuff with your response data!
            if (res.body) {
               fixCommentHeaderMore();
               var newComs = $(res.body);
               hookCommentHandlers(newComs);
               $('.comment-bodies').append(newComs);
               $('#Comment_content').val('');
            }
        });
    }
    // Always return false so that Yii will never do a traditional form submit
    return false;
}
EOS
;

Yii::app()->getClientScript()->registerScript('submitComment', $submitJS);
?>
     
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',    
    'action' => array('comment/add'),
    'clientOptions' => array('validateOnSubmit' => true,
                             'afterValidate' => 'js:submitAjax'),
	'enableAjaxValidation'=>true,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

    <?php echo $form->hiddenField($model,'obj_id'); ?>
    <?php echo $form->hiddenField($model,'obj_type'); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

