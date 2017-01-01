<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name=>array('bookkeeping/manage','slug'=>$model->slug),
  'Settings',
);

$this->menu[]=array('label'=>Yii::t('delt', 'Configuration'), 'url'=>array('bookkeeping/configure', 'slug'=>$model->slug));

if(sizeof($model->getOwners()) > 1)
{
  $this->menu[]=array('label'=>Yii::t('delt', 'Disown firm'), 'url'=>array('firm/disown', 'slug'=>$model->slug));
}


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

<h1><?php echo Yii::t('delt', 'Settings for «{name}»', array('{name}'=>$model->name)) ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>


<hr />

<?php echo $model->getLicenseCode($this) ?>
