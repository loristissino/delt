<?php
/* @var $this FirmController */
/* @var $model Firm */
/* @var $form CActiveForm */

$regenarate_anchor = addslashes(CHtml::link(Yii::t('delt', 'regenerate'), '#', array('id'=>'regenerate', 'title'=>Yii::t('delt', 'Click here if you want to regenerate the slug from the name of the firm'))));
$or_text = addslashes(Yii::t('delt', 'or'));
$randomize_anchor = addslashes(CHtml::link(Yii::t('delt', 'randomize'), '#', array('id'=>'randomize', 'title'=>Yii::t('delt', 'Click here if you want to create a random slug, which helps in keeping it somehow a bit more private'))));

$show_advanced = addslashes(Yii::t('delt', 'Show advanced options'));
$hide_advanced = addslashes(Yii::t('delt', 'Hide advanced options'));

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
        $(this).attr("style", "color: blue; font-weight: bold");
      }
      else
      {
        $(this).attr("style", "color: black");
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


$cs->registerScript(
  'advanced-options-handler',
  '

  var show_advanced = "' . $show_advanced . '";
  var hide_advanced = "' . $hide_advanced . '";

  $("#advanced").hide();
  var visible = false;
  $("#toggle-advanced-container").html("<hr><a href=\"#advanced\" id=\"toggle-advanced\">" + show_advanced + "</a>");

  $("#toggle-advanced").click( function() {
    toggleAdvanced();
    }
  );
  
  function toggleAdvanced()
  {
    console.log("toggled");
    $("#advanced").toggle("slow");
    visible = ! visible
    $("#toggle-advanced").html(visible ? hide_advanced : show_advanced);
  }
  
  
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
  'method'=>'post',
  'htmlOptions'=>array(
     'enctype'=>'multipart/form-data'
    )
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
    <?php echo $form->labelEx($model,'firmtype'); ?>
     <?php echo $form->dropDownList($model,'firmtype', $model->getValidFirmTypes()); ?>
    <br />
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'currency'); ?>
    <?php echo $form->textField($model,'currency',array('size'=>5,'maxlength'=>5)); ?><br /><span class="hint">(<?php echo Yii::t('delt', 'You must provide a three-letter <a href="http://en.wikipedia.org/wiki/ISO_4217" title="Find more on Wikipedia" target="_blank">ISO 4217 code</a>, like EUR, USD, or GBP')?>)</span>
    <?php echo $form->error($model,'currency'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'csymbol'); ?>
    <?php echo $form->textField($model,'csymbol',array('size'=>1,'maxlength'=>1)); ?><br /><span class="hint">(<?php echo Yii::t('delt', 'An optional symbol for the currency, that could be used instead of the one provided')?>)</span>
    <?php echo $form->error($model,'csymbol'); ?>
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

  <div id="toggle-advanced-container"></div>

  <div id="advanced">
  
  <?php if($model->id): ?>
    <div class="row">
      <?php echo $form->labelEx($model,'banner'); ?>
      <?php echo $form->fileField($model,'banner',array('size'=>60)); ?>
      <?php echo $form->error($model,'banner'); ?>
      <br />
      <span class="hint">
      <?php echo Yii::t('delt', 'The banner file must be an image in PNG format, width 640, height 80.') ?>
      <?php echo Yii::t('delt', 'It will be displayed on this firm\'s public page.') ?><br />
      <?php echo $this->renderPartial('_banner', array('firm'=>$model)) ?>
      </span>
    </div>
  <?php endif ?>

  <div class="row">
    <?php echo $form->labelEx($model,'checked_positions'); ?>
    <?php echo $form->textField($model,'checked_positions',array('size'=>4,'maxlength'=>10)); ?>
    <?php echo $form->error($model,'checked_positions'); ?>
    <br />
    <span class="hint"><?php echo Yii::t('delt', 'These are the positions of accounts that are checked when a journal entry is prepared, to avoid wrong outstanding balances') ?></span>
  </div>

  <div class="row checkbox">
    <?php echo $form->label($model, 'shortcodes') ?>
    <?php echo $form->checkBox($model, 'shortcodes') ?>&nbsp;
    <?php echo Yii::t('delt', 'Show only the last part of the code of the accounts.') ?>
  <br />
  <span class="hint"><?php echo Yii::t('delt', 'This could be useful when the Chart of Accounts has codes that express enough information in the last part of the code, after the last dot.') ?></span>
  </div>
  
  <div class="row">
    <?php echo $form->labelEx($model,'css'); ?>
    <?php echo $form->textArea($model,'css',array('cols'=>60, 'rows'=>5)); ?>
    <?php echo $form->error($model,'css'); ?>
  <br />
  <span class="hint"><?php echo Yii::t('delt', 'Custom Cascading Style Sheet code used when the firm is shown.') ?> <?php echo Yii::t('delt', 'It is OK to leave it empty, if you don\'t want any customization.') ?></span>
  </div>

  </div><!--advanced-->
 
  <div class="row buttons" id="submitDiv">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('delt', 'Create') : Yii::t('delt', 'Save'), array('id'=>'submitButton')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
