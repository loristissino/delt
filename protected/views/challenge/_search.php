<?php
/* @var $this ChallengeController */
/* @var $model Challenge */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'action'=>Yii::app()->createUrl($this->route),
  'method'=>'get',
)); ?>

  <div class="row">
    <?php echo $form->label($model,'id'); ?>
    <?php echo $form->textField($model,'id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'exercise_id'); ?>
    <?php echo $form->textField($model,'exercise_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'instructor_id'); ?>
    <?php echo $form->textField($model,'instructor_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'user_id'); ?>
    <?php echo $form->textField($model,'user_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'firm_id'); ?>
    <?php echo $form->textField($model,'firm_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'assigned_at'); ?>
    <?php echo $form->textField($model,'assigned_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'started_at'); ?>
    <?php echo $form->textField($model,'started_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'suspended_at'); ?>
    <?php echo $form->textField($model,'suspended_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'completed_at'); ?>
    <?php echo $form->textField($model,'completed_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'method'); ?>
    <?php echo $form->textField($model,'method'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'mark'); ?>
    <?php echo $form->textField($model,'mark'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Search'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->