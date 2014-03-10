<?php
/* @var $this EventController */
/* @var $data Event */
?>

<div class="view">

  <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
  <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
  <?php echo CHtml::encode($data->user_id); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('firm_id')); ?>:</b>
  <?php echo CHtml::encode($data->firm_id); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('action')); ?>:</b>
  <?php echo CHtml::encode($data->action); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('happened_at')); ?>:</b>
  <?php echo CHtml::encode($data->happened_at); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
  <?php echo CHtml::encode($data->content); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('referer')); ?>:</b>
  <?php echo CHtml::encode($data->referer); ?>
  <br />

  <?php /*
  <b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
  <?php echo CHtml::encode($data->address); ?>
  <br />

  */ ?>

</div>