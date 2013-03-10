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

	<?php echo $form->errorSummary($model); ?>

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
		<?php echo $form->labelEx($model,'nature') ?>
     <?php echo $form->dropDownList(
        $model, 
        'nature',
        $model->validNatures()
         )
      ?>
		<?php echo $form->error($model,'nature'); ?>
	</div>
  
  <?php if($model->is_selectable): ?>
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
  <?php endif ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
