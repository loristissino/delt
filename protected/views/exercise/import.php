<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
  'Import',
);

$this->menu=array(
  array('label'=>'View', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Edit', 'url'=>array('update', 'id'=>$model->id)),
);

?>

<h1><?php echo Yii::t('delt', 'Exercise «{name}»', array('{name}'=>$model->title)) ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'import-exercise-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?><br />
  <?php echo Yii::t('delt', 'The content must be in YAML format.') ?></p>

  <p class="note"><?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/exclamation.png') ?> <?php echo Yii::t('delt', 'Importing data to an exercise will erase all current content.') ?></p>

  <?php echo $form->errorSummary($exerciseform, Yii::t('delt', 'Please fix the following errors:')); ?>
  
  <div class="row">
    <?php echo $form->labelEx($exerciseform,'content'); ?>
    <?php echo $form->textArea($exerciseform, 'content', array('rows' => 10, 'cols' => 70)); ?>
    <?php echo $form->error($exerciseform,'content'); ?>
  </div>
  
  <div class="row buttons">
    <?php echo CHtml::submitButton(Yii::t('delt', 'Import')) ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
