<?php
$this->breadcrumbs=array(
	'Фото'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Создать запись</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>