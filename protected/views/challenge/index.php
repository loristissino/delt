<?php
/* @var $this ChallengeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
  'Challenges',
);

$this->menu=array(
  array('label'=>Yii::t('delt', 'Manage Exercises'), 'url'=>array('exercise/index')),
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

$cs->registerScript(
  'button2link',
  '
  
  

  $("#invite_others_button").after("<a id=\"invite_others_link\" href=\"#\">' . Yii::t('delt', 'Invite other users') . '</a>");
  $("#invite_others_button").hide();
  $("#invite_others_link").click(function() {
    var action = $("<input>").attr("type", "hidden").attr("name", "invite_others").val("");
    $("#choseexerciseform").append($(action)).submit();
    }
  );
    
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

<hr />

<p>
  <?php echo CHtml::link(Yii::t('delt', 'There is a list of public exercises you can try.'), $this->createUrl('site/en/challenges')) ?><br />
  <?php echo Yii::t('delt', 'If you know the slug of an exercise, you can write it here and ask for an invitation.') ?>
  
</p>
  <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'choseexerciseform',
    'enableAjaxValidation'=>false,
    'method'=>'POST',
    'action'=>$this->createUrl('challenge/getinvited'),
  )); ?>

  <div class="row">
    <?php echo CHtml::label('Slug', false) ?>
    <?php echo CHtml::textField('slug', $slug, array('size'=>40, 'value'=>$slug)) ?>
    <?php echo CHtml::submitButton(Yii::t('delt', 'Invite me!'), array('name'=>'getinvited')) ?>
    <?php if (sizeof($this->DEUser->exercises)>0): ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Invite other users'), array('id'=>'invite_others_button', 'name'=>'invite_others')) ?>
    <?php endif ?>
  </div>

<?php $this->endWidget() ?>

<hr /> 
<p><?php echo Yii::t('delt', 'If you are a teacher or an instructor, you might be interested in preparing <a href="{url}">exercises</a>.', array('{url}'=>$this->createUrl('exercise/index'))) ?></p>



