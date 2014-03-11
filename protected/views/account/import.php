<?php
/* @var $this AccountController */
/* @var $account Account */
/* @var $firm Firm */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $firm->name => array('/bookkeeping/manage', 'slug'=>$firm->slug),
  'Chart of accounts' => array('/bookkeeping/coa', 'slug'=>$firm->slug),
  'Import accounts',
);
?>

<h1><?php echo Yii::t('delt', 'Import accounts') ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'import-accounts-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?><br />
  <?php echo Yii::t('delt', 'The format for each line is: name{tab}code{tab}position{tab}balance{tab}type.') ?></p>

  <?php echo $form->errorSummary($model, Yii::t('delt', 'Please fix the following errors:')); ?>
  
  <div class="row">
    <?php echo $form->labelEx($model,'content'); ?>
    <?php echo $form->textArea($model, 'content', array('rows' => 10, 'cols' => 70)); ?>
    <?php echo $form->error($model,'content'); ?>
  </div>
  
  <div class="row buttons">
    <?php echo CHtml::submitButton(Yii::t('delt', 'Import')) ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
