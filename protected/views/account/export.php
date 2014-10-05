<?php
/* @var $this AccountController */
/* @var $firm Firm */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $firm->name => array('/bookkeeping/manage', 'slug'=>$firm->slug),
  'Chart of accounts' => array('/bookkeeping/coa', 'slug'=>$firm->slug),
  'Export accounts',
);
?>

<h1><?php echo Yii::t('delt', 'Export accounts') ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'export-accounts-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('delt', 'You can copy the contents of the following area into a spreadsheet.') ?><br />
  <?php echo Yii::t('delt', 'The format for each line is: name{tab}code{tab}position{tab}balance{tab}type{tab}children.') ?>
  </p>

  <div class="row">
    <?php echo $form->textArea($model, 'content', array('rows' => 10, 'cols' => 70)); ?>
  </div>
  
<?php $this->endWidget(); ?>

</div><!-- form -->
