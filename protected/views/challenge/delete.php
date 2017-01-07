<?php
/* @var $this ChallengeController */
/* @var $model Challenge */

$this->breadcrumbs=array(
  'Challenges'=>array('index'),
  $model->exercise->title,
  'Delete'
);

?>

<h1><?php echo $model->exercise->title ?></h1>

<p>
<?php echo Yii::t('delt', 'Are you sure you want to delete this challenge?') ?> 
<?php echo Yii::t('delt', 'The action cannot be undone.') ?></p>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'DeleteChallengeForm',
  'enableAjaxValidation'=>false,
  'method'=>'POST',
  'action'=>$this->createUrl('challenge/delete', array('id'=>$model->id)),
)); ?>

<div class="row">
  <?php echo CHtml::submitButton(Yii::t('delt', 'Delete'), array('name'=>'delete', 'class'=>'dangerous')) ?>
</div>

<?php $this->endWidget() ?>
