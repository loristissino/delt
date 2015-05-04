<?php
/* @var $this ChallengeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
  'Challenges',
);


$ajax_loader_icon=addslashes($this->createIcon('ajax-loader', Yii::t('delt', 'Please wait...'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'Please wait')), '.gif'));
$please_wait_text = addslashes(Yii::t('delt', 'The challenge is being checked.') . ' ' . Yii::t('delt', 'Please wait a few seconds...'));

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'pleasewait',
  '
  
  var ajax_loader_icon = "' . $ajax_loader_icon . '";
  var please_wait_text = "' . $please_wait_text . '";
  
  
  $(".checkButton").click(function(event)
  {
    var id = $("#"+event.target.id).data("id");
    $("#challenges").hide();
    $("#pleasewaitSpan").show();
    $("#challengeform"+id).submit();
    return true;
  }
  );
  
  $("#challenges").after(
    "<span id=\"pleasewaitSpan\">" + ajax_loader_icon + "&nbsp;" + please_wait_text + "</span>"
  );
  
  $("#pleasewaitSpan").hide();
  
  ',
  CClientScript::POS_READY
);

?>

<h1><?php echo Yii::t('delt', 'Challenges') ?></h1>

<div id="challenges">
<?php $this->widget('zii.widgets.CListView', array(
  'dataProvider'=>$dataProvider,
  'itemView'=>'_view',
)); ?>
</div>
