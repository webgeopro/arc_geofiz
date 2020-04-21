<?php
$this->breadcrumbs=array(
	'Новости'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Создать</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>