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

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'account_parent_id'); ?>
		<?php echo $form->textField($model,'account_parent_id'); ?>
		<?php echo $form->error($model,'account_parent_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'firm_id'); ?>
		<?php echo $form->textField($model,'firm_id'); ?>
		<?php echo $form->error($model,'firm_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'level'); ?>
		<?php echo $form->textField($model,'level'); ?>
		<?php echo $form->error($model,'level'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>16,'maxlength'=>16)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_selectable'); ?>
		<?php echo $form->textField($model,'is_selectable'); ?>
		<?php echo $form->error($model,'is_selectable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_economic'); ?>
		<?php echo $form->textField($model,'is_economic'); ?>
		<?php echo $form->error($model,'is_economic'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'outstanding_balance'); ?>
		<?php echo $form->textField($model,'outstanding_balance',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'outstanding_balance'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->