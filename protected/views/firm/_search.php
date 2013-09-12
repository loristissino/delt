<?php
/* @var $this FirmController */
/* @var $model Firm */
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
    <?php echo $form->label($model,'name'); ?>
    <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'slug'); ?>
    <?php echo $form->textField($model,'slug',array('size'=>32,'maxlength'=>32)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'status'); ?>
    <?php echo $form->textField($model,'status'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'currency'); ?>
    <?php echo $form->textField($model,'currency',array('size'=>5,'maxlength'=>5)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'csymbol'); ?>
    <?php echo $form->textField($model,'csymbol',array('size'=>1,'maxlength'=>1)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'language_id'); ?>
    <?php echo $form->textField($model,'language_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'firm_parent_id'); ?>
    <?php echo $form->textField($model,'firm_parent_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'create_date'); ?>
    <?php echo $form->textField($model,'create_date'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Search'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->