<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Delete',
);

?>

<h1><?php echo $model->name ?></h1>

<p>
<?php echo Yii::t('delt', 'Are you sure you want to delete this firm?') ?> 
<?php echo Yii::t('delt', 'The action cannot be undone.') ?></p>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'DeleteFirmForm',
  'enableAjaxValidation'=>false,
  'method'=>'POST',
  'action'=>$this->createUrl('firm/delete', array('slug'=>$model->slug)),
)); ?>

<div class="row">
  <?php echo CHtml::submitButton(Yii::t('delt', 'Delete'), array('name'=>'delete', 'class'=>'dangerous')) ?>
</div>

<?php $this->endWidget() ?>

