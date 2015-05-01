<?php
/* @var $this ChallengeController */
/* @var $model Challenge */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'challenge-form',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // There is a call to performAjaxValidation() commented in generated controller code.
  // See class documentation of CActiveForm for details on this.
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'exercise_id'); ?>
    <?php echo $form->textField($model,'exercise_id'); ?>
    <?php echo $form->error($model,'exercise_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'instructor_id'); ?>
    <?php echo $form->textField($model,'instructor_id'); ?>
    <?php echo $form->error($model,'instructor_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'user_id'); ?>
    <?php echo $form->textField($model,'user_id'); ?>
    <?php echo $form->error($model,'user_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'firm_id'); ?>
    <?php echo $form->textField($model,'firm_id'); ?>
    <?php echo $form->error($model,'firm_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'assigned_at'); ?>
    <?php echo $form->textField($model,'assigned_at'); ?>
    <?php echo $form->error($model,'assigned_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'started_at'); ?>
    <?php echo $form->textField($model,'started_at'); ?>
    <?php echo $form->error($model,'started_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'suspended_at'); ?>
    <?php echo $form->textField($model,'suspended_at'); ?>
    <?php echo $form->error($model,'suspended_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'completed_at'); ?>
    <?php echo $form->textField($model,'completed_at'); ?>
    <?php echo $form->error($model,'completed_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'method'); ?>
    <?php echo $form->textField($model,'method'); ?>
    <?php echo $form->error($model,'method'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'mark'); ?>
    <?php echo $form->textField($model,'mark'); ?>
    <?php echo $form->error($model,'mark'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->