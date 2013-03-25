<?php
/* @var $this FirmController */
/* @var $model Firm */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'firm-form',
	'enableAjaxValidation'=>false,
  'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note"><?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/exclamation.png') ?> <?php echo Yii::t('delt', 'Importing data to a firm will erase all current content.') ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'file'); ?>
		<?php echo $form->fileField($model,'file',array('size'=>50)); ?>
		<?php echo $form->error($model,'file'); ?>
	</div>
  
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('delt', 'Import')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
