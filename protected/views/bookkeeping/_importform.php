<?php
/* @var $this FirmController */
/* @var $model Firm */
/* @var $form CActiveForm */

$ajax_loader_icon=addslashes($this->createIcon('ajax-loader', Yii::t('delt', 'Please wait...'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'Please wait')), '.gif'));
$please_wait_text = addslashes(Yii::t('delt', 'The data are being imported.') . ' ' . Yii::t('delt', 'Please wait a few seconds...'));

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'pleasewait',
  '
  
  var ajax_loader_icon = "' . $ajax_loader_icon . '";
  var please_wait_text = "' . $please_wait_text . '";
  
  $("#submitButton").click(function()
  {
    $("#submitButton").hide();
    $("#pleasewaitSpan").show();
    $("form#myform").submit();
    return false;
  }
  )
  
  $("#submitDiv").append(
    "<span id=\"pleasewaitSpan\">" + ajax_loader_icon + "&nbsp;" + please_wait_text + "</span>"
  );
  $("#pleasewaitSpan").hide();
  
  ',
  CClientScript::POS_READY
);




?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'myform',
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
  
	<div class="row buttons" id="submitDiv">
		<?php echo CHtml::submitButton(Yii::t('delt', 'Import'), array('id'=>'submitButton')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
