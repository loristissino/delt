<?php
/* @var $this TransactionController */
/* @var $model Transaction */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'transaction-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php //echo $form->labelEx($model,'exercise_id'); ?>
		<?php echo $form->hiddenField($model,'exercise_id'); ?>
		<?php echo $form->error($model,'exercise_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'event_date'); ?>
		<?php echo $form->textField($model,'event_date', array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'event_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rank'); ?>
		<?php echo $form->numberField($model,'rank', array('min'=>0,'max'=>1000)); ?>
		<?php echo $form->error($model,'rank'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?><span class="hint"><?php echo Yii::t('delt', 'You can use Markdown syntax in this field.') ?></span>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>70)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hint'); ?><span class="hint"><?php echo Yii::t('delt', 'You can use Markdown syntax in this field.') ?></span>
		<?php echo $form->textArea($model,'hint',array('rows'=>6, 'cols'=>70)); ?>
		<?php echo $form->error($model,'hint'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'points'); ?>
		<?php echo $form->numberField($model,'points', array('min'=>1,'max'=>1000)); ?>
		<?php echo $form->error($model,'points'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'penalties'); ?>
		<?php echo $form->numberField($model,'penalties', array('min'=>0,'max'=>1000)); ?>
		<?php echo $form->error($model,'penalties'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
