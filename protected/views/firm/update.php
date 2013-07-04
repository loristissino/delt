<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
	'Firms'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$ajax_loader_icon=addslashes($this->createIcon('ajax-loader', Yii::t('delt', 'Please wait...'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'Please wait')), '.gif'));
$please_wait_text = addslashes(Yii::t('delt', 'The information about the firm is being saved.') . ' ' . Yii::t('delt', 'Please wait a few seconds...'));

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

<h1><?php echo Yii::t('delt', 'Edit Firm «{name}»', array('{name}'=>$model->name)) ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>


<hr />

<?php echo $model->getLicenseCode($this) ?>
