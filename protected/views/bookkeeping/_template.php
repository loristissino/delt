<?php
/* @var $this TemplateController */
/* @var $model Template */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'template-_template-form',
  'enableAjaxValidation'=>false,
)); ?>


  <p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?></p>
  <?php echo $form->errorSummary($model); ?>

  <p><?php echo Yii::t('delt', 'You are going to create a new template with the following accounts:') ?></p>
  <?php
    $methods=Template::model()->getMethods();
    foreach($this->journalentry->postings as $posting): $type=DELT::amount2type($posting->amount) ?>
    <?php echo CHtml::dropDownList(
      'method['.$posting->account_id . ']',
      $posting->account_id==Yii::app()->getUser()->getState('last_account_closed_interactively')?'?':'$', 
      $methods) ?>
    <?php echo $posting->account->getCodeAndName($firm) ?> (<?php echo Yii::t('delt', $type) ?>)
    <br />
  <?php endforeach ?>

  <div class="row">
    <?php echo $form->labelEx($model,'description'); ?>
    <?php echo $form->textField($model,'description', array('size'=>100)); ?>
    <?php echo $form->error($model,'description'); ?>
  </div>

  <div class="row checkbox">
    <?php echo $form->label($model, 'automatic') ?>
    <?php echo $form->checkBox($model, 'automatic') ?>&nbsp;
    <?php echo Yii::t('delt', 'Apply this template automatically.') ?>
  <br />
  <span class="hint"><?php echo Yii::t('delt', 'This is useful for some closing entries you could wish to be automatically and virtually prepared each time a financial statement is prepared (without actually seeing the journal entry).') ?></span>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton(Yii::t('delt', 'Create')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
