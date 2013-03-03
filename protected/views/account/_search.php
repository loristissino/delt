<?php
/* @var $this AccountController */
/* @var $model Account */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'account_parent_id'); ?>
		<?php echo $form->textField($model,'account_parent_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'firm_id'); ?>
		<?php echo $form->textField($model,'firm_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'level'); ?>
		<?php echo $form->textField($model,'level'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>16,'maxlength'=>16)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_selectable'); ?>
		<?php echo $form->textField($model,'is_selectable'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_economic'); ?>
		<?php echo $form->textField($model,'is_economic'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'outstanding_balance'); ?>
		<?php echo $form->textField($model,'outstanding_balance',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->