<?php
/* @var $this ExerciseController */
/* @var $model Exercise */
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
    <?php echo $form->label($model,'user_id'); ?>
    <?php echo $form->textField($model,'user_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'firm_id'); ?>
    <?php echo $form->textField($model,'firm_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'slug'); ?>
    <?php echo $form->textField($model,'slug',array('size'=>32,'maxlength'=>32)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'title'); ?>
    <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'description'); ?>
    <?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'introduction'); ?>
    <?php echo $form->textArea($model,'introduction',array('rows'=>6, 'cols'=>50)); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Search'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->