<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
  'Invite',
);

$this->menu=array(
  array('label'=>'List Exercises', 'url'=>array('index')),
  array('label'=>'View Exercise', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>«<?php echo $model->title ?>»: <?php echo Yii::t('delt', 'invite users') ?></h1>

<div class="form" style="width: 700px">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'exerciseform_'. $model->id,
  'enableAjaxValidation'=>false,
  'method'=>'POST',
  'action'=>array('invite', 'id'=>$model->id),
)); ?>


  <div class="row">
    <?php echo CHtml::label(Yii::t('delt', 'List of usernames'), 'users') ?>
    <?php echo CHtml::textArea('users', '', array('cols'=>30, 'rows'=>10)) ?>
  </div>

  <div class="row">
    <?php echo CHtml::label(Yii::t('delt', 'Session'), 'session') ?>
    <?php echo CHtml::textField('session', date('Ymd')) ?>
  </div>

  <div class="row">
    <?php echo CHtml::label(Yii::t('delt', 'Method'), 'method') ?>
    <?php echo CHtml::textField('method', '61') ?>
  </div>

<div class="actions buttons">
  <?php echo CHtml::submitButton(Yii::t('delt', 'Invite'), array('name'=>'invite')) ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
