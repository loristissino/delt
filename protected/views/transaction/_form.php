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

  <p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php //echo $form->labelEx($model,'exercise_id'); ?>
		<?php echo $form->hiddenField($model,'exercise_id'); ?>
		<?php echo $form->error($model,'exercise_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'event_date'); ?>
		<?php //echo $form->textField($model,'event_date', array('size'=>10,'maxlength'=>10)); ?>

    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
      'name'=>'Transaction[event_date]',
      'value'=>$model->event_date,
      'language'=>Yii::app()->language,
      'options'=>array(
          'showAnim'=>'fold', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
          'showOn'=>'both', // 'focus', 'button', 'both'
          'buttonText'=>Yii::t('delt','Select date from calendar'),
          'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
          'buttonImageOnly'=>true,
      ),
      'htmlOptions'=>array(
          'style'=>'width:80px;vertical-align:top',
          'class'=>'datepicker',
      ),
    ));
    ?>
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
		<?php echo $form->labelEx($model,'entries'); ?>
		<?php echo $form->numberField($model,'entries', array('min'=>0,'max'=>1000)); ?>
		<?php echo $form->error($model,'entries'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hint'); ?><span class="hint"><?php echo Yii::t('delt', 'You can use Markdown syntax in this field.') ?></span>
		<?php echo $form->textArea($model,'hint',array('rows'=>6, 'cols'=>70)); ?>
		<?php echo $form->error($model,'hint'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'regexps'); ?><span class="hint"><?php echo Yii::t('delt', 'Regular expressions to match journal entries descriptions with.') ?></span>
		<?php echo $form->textArea($model,'regexps',array('rows'=>6, 'cols'=>70)); ?>
		<?php echo $form->error($model,'regexps'); ?>
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
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('delt', 'Create') : Yii::t('delt', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
