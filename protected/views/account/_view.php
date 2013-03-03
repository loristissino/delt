<?php
/* @var $this AccountController */
/* @var $data Account */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('account_parent_id')); ?>:</b>
	<?php echo CHtml::encode($data->account_parent_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('firm_id')); ?>:</b>
	<?php echo CHtml::encode($data->firm_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('level')); ?>:</b>
	<?php echo CHtml::encode($data->level); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_selectable')); ?>:</b>
	<?php echo CHtml::encode($data->is_selectable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_economic')); ?>:</b>
	<?php echo CHtml::encode($data->is_economic); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('outstanding_balance')); ?>:</b>
	<?php echo CHtml::encode($data->outstanding_balance); ?>
	<br />

	*/ ?>

</div>