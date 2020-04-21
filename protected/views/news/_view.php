<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('short')); ?>:</b>
	<?php echo CHtml::encode($data->short); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('full')); ?>:</b>
	<?php echo CHtml::encode($data->full); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />


</div>