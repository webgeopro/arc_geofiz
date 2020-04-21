<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'news-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'short'); ?>
		<?php echo $form->textField($model,'short',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'short'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'full'); ?>
		<?php echo $form->textArea($model,'full',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'full'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->