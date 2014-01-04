<?php
/* @var $this AccountController */
/* @var $model Account */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'account-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?></p>

  <?php echo $form->errorSummary($model, Yii::t('delt', 'Please fix the following errors:')); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'code'); ?>
    <?php echo $form->textField($model,'code',array('size'=>16,'maxlength'=>16)); ?>
    <?php echo $form->error($model,'code'); ?>
  </div>
  
  <div class="row">
    <?php echo $form->labelEx($model,'textnames'); ?>
    <?php echo $form->textArea($model, 'textnames', array('maxlength' => 300, 'rows' => 4, 'cols' => 50)); ?>
    <?php echo $form->error($model,'textnames'); ?>
  </div>
  
  <div class="row">
    <?php echo $form->labelEx($model,'comment'); ?>
    <?php echo $form->textArea($model, 'comment', array('maxlength' => 300, 'rows' => 4, 'cols' => 50)); ?>
    <?php echo $form->error($model,'comment'); ?>
  </div>

  <div class="row">
    <?php if($model->is_hidden): ?>
      <?php echo $form->labelEx($model,'position'); ?>
      <?php echo $form->textField($model,'position',array('size'=>1,'maxlength'=>1)); ?>
      <?php echo $form->error($model,'position'); ?>
    <?php else: ?>
      <?php echo $form->labelEx($model,'position') ?>
       <?php echo $form->dropDownList(
          $model, 
          'position',
          $model->validpositions(false)
           )
        ?>
      <?php echo $form->error($model,'position'); ?>
    <?php endif ?>
  
  </div>
  
  <div class="row">
    <?php echo $form->labelEx($model,'outstanding_balance'); ?>
     <?php echo $form->dropDownList(
        $model, 
        'outstanding_balance',
        array(
          '/'=>Yii::t('delt', 'unset'),
          'D'=>Yii::t('delt', 'Debit'),
          'C'=>Yii::t('delt', 'Credit'),
          ) 
         )
      ?>
    <?php echo $form->error($model,'outstanding_balance'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('delt', 'Create') : Yii::t('delt', 'Save')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
