<?php
/* @var $this SectionController */
/* @var $model Section */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'section-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
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
		<?php echo $form->labelEx($model,'is_visible'); ?>
		<?php echo $form->textField($model,'is_visible'); ?>
		<?php echo $form->error($model,'is_visible'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rank'); ?>
		<?php echo $form->textField($model,'rank'); ?>
		<?php echo $form->error($model,'rank'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'color'); ?>
    <?php
    $this->widget('application.extensions.colorpicker.EColorPicker', 
                array(
                      'name'=>'Section[color]',
                      'mode'=>'textfield',
                      'fade' => false,
                      'slide' => false,
                      'curtain' => false,
                      'value' => $model->color,
                     )
               );
    ?>
		<?php echo $form->error($model,'color'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('delt', 'Create') : Yii::t('delt', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
