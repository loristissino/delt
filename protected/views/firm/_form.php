<?php
/* @var $this FirmController */
/* @var $model Firm */
/* @var $form CActiveForm */

$regenarate_anchor = addslashes(CHtml::link(Yii::t('delt', 'regenerate'), '#', array('id'=>'regenerate', 'title'=>Yii::t('delt', 'Click here if you want to regenerate the slug from the name of the firm'))));
$or_text = addslashes(Yii::t('delt', 'or'));
$randomize_anchor = addslashes(CHtml::link(Yii::t('delt', 'randomize'), '#', array('id'=>'randomize', 'title'=>Yii::t('delt', 'Click here if you want to create a random slug, which helps in keeping it somehow a bit more private'))));

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/slugifier.js', CClientScript::POS_END);
$cs->registerScript(
  'slugify-handler',
  '
    
  var slugified = ' . ($model->slug ? 'true':'false') . ';
  
  function slugit()
  {
     $("#Firm_slug").val($("#Firm_name").val().slugify().substring(0,32));
  }
  
  function randomString(len, charSet) {
    charSet = charSet || "abcdefghijklmnopqrstuvwxyz0123456789";
    var randomString = "";
    for (var i = 0; i < len; i++) {
      var randomPoz = Math.floor(Math.random() * charSet.length);
      randomString += charSet.substring(randomPoz,randomPoz+1);
    }
    return randomString;
  }
  
  $("#Firm_name").keyup(function()
    {
      if(!slugified)
      {
        slugit();
      }
    }
  );
  
  $("#Firm_slug").change(function()
    {
      slugified=true;
    }
  );
  
  $("#Firm_slug").after("&nbsp;' . $or_text . '&nbsp;' . $randomize_anchor .'");
  
  $("#randomize").click(function()
    {
      slugified = true;
      $("#Firm_slug").val(randomString(32));
      return false;
    }
  );
  
  $("#Firm_slug").after("&nbsp;' . $regenarate_anchor .'");
  
  $("#regenerate").click(function()
    {
      slugit();
      slugified = false;
      return false;
    }
  );
  
  '
  ,
  CClientScript::POS_READY
);

$cs->registerScript(
  'language-handler',
  '

  var applyCss = function()
  {
    var id = $("#Firm_language_id").val();
    $("#Firm_languages").children().each(function()
    {
      if($(this).val() == id)
      {
        $(this).attr("style", "padding: 2em; color: blue; font-weight: bold");
      }
      else
      {
        $(this).attr("style", "padding: 0em; color: black");
      }
    }
    )
    ;
  }

  applyCss();

  var checkConsistency = function()
  {
    var id = $("#Firm_language_id").val();
    var current = $("#Firm_languages").val();
    if($.inArray(id, current)<=-1)
    {
      if ( null == current )
      {
        current = [ id ];
      }
      else
      {
        current.push(id);
      }
      $("#Firm_languages").val(current);
    }
    applyCss();
  }

    
  $("#Firm_language_id").change(checkConsistency);

  $("#Firm_languages").change(checkConsistency);


  
  '
  ,
  CClientScript::POS_READY
);


$languages_options=array();

if($model->isNewRecord)
{
  foreach(Language::model()->findAllSorted(true) as $language)
  {
    $languages_options[$language->id] = array('selected'=>'selected');
  }
  $model->language = Language::model()->findByAttributes(array('is_default'=>2));
  $language_option = array($model->language->id => array('selected'=>'selected'));
}
else
{
  foreach($model->languages as $language)
  {
    $languages_options[$language->id] = array('selected'=>'selected');
  } 
}

$languages_available = Language::model()->findAllSorted();

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'myform',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?></p>

  <?php echo $form->errorSummary($model, Yii::t('delt', 'Please fix the following errors:')); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'name'); ?>
    <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'name'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'description'); ?>
    <?php echo $form->textArea($model,'description',array('cols'=>60, 'rows'=>5)); ?>
    <?php echo $form->error($model,'description'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'slug'); ?>
    <?php echo $form->textField($model,'slug',array('size'=>32,'maxlength'=>32)); ?><br /><span class="hint">(<?php echo Yii::t('delt', 'Wonder what a <a href="http://en.wikipedia.org/wiki/Slug_(web_publishing)#Slug" title="A slug is the part of a URL which identifies a page using human-readable keywords." target="_blank">slug</a> is?') ?>)</span>
    <?php echo $form->error($model,'slug'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'currency'); ?>
    <?php echo $form->textField($model,'currency',array('size'=>5,'maxlength'=>5)); ?><br /><span class="hint">(<?php echo Yii::t('delt', 'You must provide a three-letter <a href="http://en.wikipedia.org/wiki/ISO_4217" title="Find more on Wikipedia" target="_blank">ISO 4217 code</a>, like EUR, USD, or GBP')?>)</span>
    <?php echo $form->error($model,'currency'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'language_id'); ?>
     <?php echo $form->dropDownList($model,'language_id', CHtml::listData(Language::model()->findAllSorted(),
        'id', //this is the attribute name for list option values 
        'complete_name' // this is the attribute name for list option texts 
         ),
         (isset($language_option) ? array('options'=>$language_option) : array())
      ); ?>
    <br />
    <span class="hint">(<?php echo Yii::t('delt', 'The language is used for the names of the accounts, not for the user interface')?>)</span>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'languages'); ?>
    <?php echo $form->listBox($model,'languages', CHtml::listData($languages_available, 'id', 'complete_name'), array('multiple'=>'multiple', 'size'=>sizeof($languages_available), 'options'=>$languages_options)) ?>
    <br />
    <span class="hint">(<?php echo Yii::t('delt', 'You can select other languages to have a multilingual chart of accounts.') ?>
    <?php echo Yii::t('delt', 'Do you want other languages / locales to be supported?')?>
    <?php echo Yii::t('delt', 'Just <a href="{url}" target="_blank">drop us a message</a>!', array('{url}'=>$this->createUrl('site/contact'))) ?>)</span>
  </div>
  
  <?php if(!$model->id): ?>
    <div class="row checkbox">
      <?php echo $form->label($model, 'license') ?>
      <?php echo $form->checkBox($model, 'license_confirmation') ?>&nbsp;
      <?php echo Yii::t('delt', 'I agree on the fact that the contents of the firm I\'m creating will be available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}" target="_blank">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.', array('{locale}'=>Yii::app()->language)) ?>
    <br />
    <span class="hint">(<?php echo Yii::t('delt', 'Curious about <a href="{url}" target="_blank">why</a> you have to accept a Creative Commons License?', array('{url}'=>$this->createUrl('site/en/cclicense'))) ?>)</span>
    </div>
  <?php endif ?>
  
  <div class="row buttons" id="submitDiv">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('delt', 'Create') : Yii::t('delt', 'Save'), array('id'=>'submitButton')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
