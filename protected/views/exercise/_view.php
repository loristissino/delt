<?php
/* @var $this ExerciseController */
/* @var $data Exercise */
?>

<div class="view">

  <h3><?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id'=>$data->id)); ?></h3>

  <b><?php echo Yii::t('delt', 'Benchmark Firm') ?>:</b>
  <?php echo CHtml::link(CHtml::encode($data->firm), array('bookkeeping/manage', 'slug'=>$data->firm->slug)); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
  <?php echo CHtml::encode($data->description); ?>
  <br />

</div>
