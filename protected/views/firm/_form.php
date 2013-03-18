<?php
/* @var $this FirmController */
/* @var $model Firm */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'firm-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('cols'=>60, 'rows'=>5)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'slug'); ?>
		<?php echo $form->textField($model,'slug',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'slug'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'currency'); ?>
		<?php echo $form->textField($model,'currency',array('size'=>5,'maxlength'=>5)); ?> (<?php echo CHtml::link(Yii::t('delt', 'ISO 4217 code'), 'http://en.wikipedia.org/wiki/ISO_4217', array('target'=>'_new', 'title'=>Yii::t('delt', 'See the Wikipedia page to find out the code of the currency you want to use'))) ?>)
		<?php echo $form->error($model,'currency'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'language_id'); ?>
     <?php echo $form->dropDownList($model,'language_id', CHtml::listData(Language::model()->findAll(),
        'id', //this is the attribute name for list option values 
        'complete_name' // this is the attribute name for list option texts 
         )
      ); ?>
	</div>
  
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('delt', 'Create') : Yii::t('delt', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
