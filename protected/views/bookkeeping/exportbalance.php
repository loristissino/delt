<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Balance' => array('/bookkeeping/balance', 'slug'=>$model->slug),
  'Export', 
);

?>
<h1><?php echo Yii::t('delt', 'Trial Balance Export to CSV file') ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'template-_template-form',
  'method'=>'GET',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?></p>

  <?php echo $form->errorSummary($exportbalanceform); ?>

  <div class="row">
    <?php echo $form->labelEx($exportbalanceform, 'type') ?>
     <?php echo $form->dropDownList(
        $exportbalanceform, 
        'type',
        $exportbalanceform->types
        )
      ?>
    <?php echo $form->error($model, 'type'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($exportbalanceform, 'charset') ?>
     <?php echo $form->dropDownList(
        $exportbalanceform, 
        'charset',
        $exportbalanceform->charsets
        )
      ?>
    <?php echo $form->error($model, 'charset'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($exportbalanceform, 'delimiter') ?>
     <?php echo $form->dropDownList(
        $exportbalanceform, 
        'delimiter',
        $exportbalanceform->delimiters
        )
      ?>
    <?php echo $form->error($model, 'delimiter'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($exportbalanceform, 'separator') ?>
     <?php echo $form->dropDownList(
        $exportbalanceform, 
        'separator',
        $exportbalanceform->separators
        )
      ?>
    <?php echo $form->error($model, 'separator'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($exportbalanceform,'fruition'); ?>
    <?php echo $form->radioButtonList($exportbalanceform, 'fruition', $exportbalanceform->fruitions, array('template'=>'{input} {label}', 'labelOptions'=>array('style'=>'display: inline; font-weight: normal'), 'separator'=>'&nbsp;&nbsp;&nbsp;', 'container'=>'')) ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($exportbalanceform,'inclusion'); ?>
    <?php echo $form->radioButtonList($exportbalanceform, 'inclusion', $exportbalanceform->inclusions, array('template'=>'{input} {label}', 'labelOptions'=>array('style'=>'display: inline; font-weight: normal'), 'separator'=>'&nbsp;&nbsp;&nbsp;', 'container'=>'')) ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton(Yii::t('delt', 'Export'), array('name'=>'export')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
