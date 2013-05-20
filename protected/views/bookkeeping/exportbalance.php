<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Balance',
);

?>
<h1><?php echo Yii::t('delt', 'Trial Balance') ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reason-_reason-form',
  'method'=>'GET',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?></p>

	<?php echo $form->errorSummary($exportbalanceform); ?>

	<div class="row">
		<?php echo $form->labelEx($exportbalanceform, 'delimiter') ?>
     <?php echo $form->dropDownList(
        $exportbalanceform, 
        'delimiter',
        $exportbalanceform->delimiters
        )
      ?>
		<?php echo $form->error($model, 'delimiter'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($exportbalanceform, 'separator') ?>
     <?php echo $form->dropDownList(
        $exportbalanceform, 
        'separator',
        $exportbalanceform->separators
        )
      ?>
		<?php echo $form->error($model, 'separator'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($exportbalanceform, 'type') ?>
     <?php echo $form->dropDownList(
        $exportbalanceform, 
        'type',
        $exportbalanceform->types
        )
      ?>
		<?php echo $form->error($model, 'type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('delt', 'Export'), array('name'=>'export')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
