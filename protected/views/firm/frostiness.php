<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  $model->frozen_at ? 'Unfreeze' : 'Freeze',
);

$linkedToCompletedChallenge = false;
if ($challenge = $model->getChallenge())
{
  if ($challenge->isCompleted())
  {
    $linkedToCompletedChallenge = true;
  }
}

?>

<h1><?php echo $model->name ?></h1>

<?php echo $this->renderPartial('_frostiness', array('model'=>$model, 'warning'=>$linkedToCompletedChallenge)) ?>

<?php if(!$model->frozen_at): ?>

  <p><?php echo Yii::t('delt', 'You can freeze it, if you want. You will not be able to work on it until it gets unfrozen.') ?></p>
  <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'FreezeFirmForm',
    'enableAjaxValidation'=>false,
    'method'=>'POST',
    'action'=>$this->createUrl('firm/freeze', array('slug'=>$model->slug)),
  )); ?>

    <div class="row">
      <?php echo CHtml::submitButton(Yii::t('delt', 'Freeze'), array('name'=>'freeze')) ?>
    </div>

  <?php $this->endWidget() ?>

<?php else: ?>
  <p><?php echo Yii::t('delt', 'You can unfreeze it, if you want.') ?></p>
  <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'FreezeFirmForm',
    'enableAjaxValidation'=>false,
    'method'=>'POST',
    'action'=>$this->createUrl('firm/unfreeze', array('slug'=>$model->slug)),
  )); ?>

    <div class="row">
      <?php echo CHtml::submitButton(Yii::t('delt', 'Unfreeze'), array('name'=>'unfreeze', 'class'=>($linkedToCompletedChallenge?'dangerous':''))) ?>
    </div>

  <?php $this->endWidget() ?>

<?php endif ?>


