<div class="form">
<?php

$form = $this->beginWidget(
    'CActiveForm',
    array(
        'id' => 'upload-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )
);

?>
<p class="note">Fields with <span class="required">*</span> are required.</p>
<?php

echo CHtml::errorSummary($model); 
?>
<div class="row">
<?php
echo $form->labelEx($model, 'description');
echo CHtml::activeTextArea($model,'description',array('rows'=>10, 'cols'=>70)); 
echo $form->error($model, 'description');
?>
</div>
<div class="row">
<?php


echo $form->labelEx($model, 'fileModel');
echo $form->fileField($model, 'fileModel');
echo $form->error($model, 'fileModel');

?>
</div>
<?php

echo $form->hiddenField($model, 'project_id');
?>
<div class="row buttons">
    <?php echo CHtml::submitButton('Upload', array('class' => 'upload-button')); ?>
    <?php echo CHtml::submitButton('Cancel', array('class' => 'cancel-upload')); ?>    
</div>
<?php
$this->endWidget();
?>
</div>