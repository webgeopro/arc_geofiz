<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'photo-category-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, помеченные <span class="required">*</span> обязательны к заполнению.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'photo_id'); ?>
		<?//$form->textField($model,'photo_id',array('size'=>10,'maxlength'=>10)); ?>
         <?=CHtml::activeDropDownList($model,'photo_id',CHtml::listData(Photo::model()->findAll(),'id','name'));?>
		<?php echo $form->error($model,'photo_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'category'); ?>
		<?//$form->textField($model,'category'); ?>
         <?=CHtml::activeDropDownList($model,'category',CHtml::listData(Pages::model()->findAll(),'id','label'));?>
		<?php echo $form->error($model,'category'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->