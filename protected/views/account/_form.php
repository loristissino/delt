<?php
/* @var $this AccountController */
/* @var $model Account */
/* @var $form CActiveForm */

$disabled = $model->level==1 ? 'false':'true'; //initial status

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'hidden-accounts-handler',
  '
  
  var fixtypefield = function(is_disabled)
  {
    $("#typefield").prop("disabled", is_disabled);
  }
  
  $("#Account_code").blur(function()
    {
        fixtypefield($("#Account_code").val().indexOf(".")>=0);
    }
  );

  $("#Account_code").focus().val($("#Account_code").val());
  
  fixtypefield(' . $disabled . ');
  
  '
  ,
  CClientScript::POS_READY
);


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
    <?php if($model->isHidden()): ?>
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

  <div class="row checkbox">
    <?php echo $form->label($model, 'subchoices') ?>
    <?php echo $form->checkBox($model, 'subchoices') ?>&nbsp;
    <?php echo Yii::t('delt', 'This account admits subchoices.') ?>
  </div>

  <?php if($model->isHidden()): ?>
    <div class="row" id="typerow">
      <?php echo $form->labelEx($model,'type'); ?>
       <?php echo $form->dropDownList(
          $model, 
          'type',
          array(
            '1'=>Yii::t('delt', 'Statement in pancake format'),
            '2'=>Yii::t('delt', 'Statement in two separate sections'),
            '3'=>Yii::t('delt', 'Statement in analytic format')
            ),
          array(
            'id'=>'typefield')
           )
        ?>
      <?php echo $form->error($model,'outstanding_balance'); ?>
    </div>
  <?php endif ?>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('delt', 'Create') : Yii::t('delt', 'Save')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
