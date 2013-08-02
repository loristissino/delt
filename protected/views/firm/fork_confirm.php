<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
	'Firms'=>array('index'),
	'Fork',
);

$ajax_loader_icon=addslashes($this->createIcon('ajax-loader', Yii::t('delt', 'Please wait...'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'Please wait')), '.gif'));
$please_wait_text = addslashes(Yii::t('delt', 'The firm is being forked.') . ' ' . Yii::t('delt', 'Please wait a few seconds...'));

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

<h1><?php echo Yii::t('delt', 'Fork the firm «{firm}»', array('{firm}'=>$firm)) ?></h1>

<h2><?php echo Yii::t('delt', 'Description') ?></h2>

<p><?php echo $firm->description ?></p>

<hr />

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array('id'=>'myform')); ?>

	<?php echo $form->errorSummary($forkfirmform, Yii::t('delt', 'Please fix the following errors:')); ?>

    <div class="row buttons">
      <?php echo $form->label($forkfirmform, 'type'); ?><?php echo $form->dropDownList($forkfirmform,
          'type',
          $forkfirmform->getTypeOptions()
          ) ?>
          <hr />
    </div>
    <div class="row checkbox">
      <?php echo $form->labelEx($forkfirmform,'license'); ?>
      <?php echo $form->checkBox($forkfirmform, 'license_confirmation') ?>&nbsp;
      <?php echo Yii::t('delt', 'I agree on the fact that the contents of the firm I\'m creating will be available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}" target="_blank">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.', array('{locale}'=>Yii::app()->language)) ?>
      <br />
    <span class="hint">(<?php echo Yii::t('delt', 'Curious about <a href="{url}" target="_blank">why</a> you have to accept a Creative Common License?', array('{url}'=>$this->createUrl('site/en/cclicense'))) ?>)</span>
        <hr />
    </div>
    <div class="row">
    <p><?php echo Yii::t('delt', 'Do you want to proceed?') ?></p>
    </div>
    <div class="row submit" id="submitDiv">
        <?php echo CHtml::submitButton(Yii::t('delt', 'Yes, please, fork this firm'), array('id'=>'submitButton')) ?>
    </div>

      <hr />
      
    
<?php $this->endWidget(); ?>
</div><!-- form -->

