<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'photo_id'); ?>
		<?//php echo $form->textField($model,'photo_id',array('size'=>10,'maxlength'=>10)); ?>
        <?//=$form->textField($model->photo,'name',array('size'=>10,'maxlength'=>10)); ?>
        <?//=CHtml::activeTextField($model->photo,'name',array('size'=>10,'maxlength'=>10));?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'category'); ?>
		<?//php echo $form->textField($model,'category'); ?>
        <?//=CHtml::encode($model->pages->label); ?>
        <?//=CHtml::activeDropDownList($model,'category',CHtml::listData(Pages::model()->findAll(),'id','label'));?> 
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search');?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->