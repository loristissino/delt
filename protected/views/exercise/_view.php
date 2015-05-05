<?php
/* @var $this ExerciseController */
/* @var $data Exercise */
?>

<div class="view">

  <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
  <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
  <br />

  <b><?php echo Yii::t('delt', 'Firm') ?></b>
  <?php echo CHtml::encode($data->firm); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('slug')); ?>:</b>
  <?php echo CHtml::encode($data->slug); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
  <?php echo CHtml::encode($data->title); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
  <?php echo CHtml::encode($data->description); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('introduction')); ?>:</b>
  <?php echo CHtml::encode($data->introduction); ?>
  <br />

  <p><?php echo CHtml::link('Invite', array('exercise/invite', 'id'=>$data->id)) ?></p>

</div>
