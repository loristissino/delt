<?php
/* @var $this ExerciseController */
/* @var $model Exercise */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'exercise-form',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // There is a call to performAjaxValidation() commented in generated controller code.
  // See class documentation of CActiveForm for details on this.
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?php echo $form->errorSummary($model); ?>

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
    <?php echo $form->labelEx($model,'slug'); ?>
    <?php echo $form->textField($model,'slug',array('size'=>32,'maxlength'=>32)); ?>
    <?php echo $form->error($model,'slug'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'title'); ?>
    <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'title'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'description'); ?>
    <?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'description'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'introduction'); ?>
    <?php echo $form->textArea($model,'introduction',array('rows'=>6, 'cols'=>50)); ?>
    <?php echo $form->error($model,'introduction'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->