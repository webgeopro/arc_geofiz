<?php
$this->breadcrumbs=array(
	'Photos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Photo', 'url'=>array('index')),
	array('label'=>'Manage Photo', 'url'=>array('admin')),
    array('label'=>'Download Image', 'url'=>array('download')),
);
?>

<h1>Загрузка изображения</h1>

<div class="form">
    <?=CHtml::form('','post',array('enctype'=>'multipart/form-data')); ?>
        <?=CHtml::activeFileField($model, 'image'); ?>
        <br />
        <?=CHtml::ajaxSubmitButton('Save')?>
    <?=CHtml::endForm(); ?>
</div><!-- form -->