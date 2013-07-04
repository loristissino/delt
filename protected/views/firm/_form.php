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


$languages_options=array();
foreach($model->languages as $language)
{
  $languages_options[$language->id] = array('selected'=>'selected');
}

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'firm-form',
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
		<?php echo $form->textField($model,'slug',array('size'=>32,'maxlength'=>32)); ?><br /><span class="hint">(<?php echo Yii::t('delt', 'Wonder what a <a href="http://en.wikipedia.org/wiki/Slug_(web_publishing)#Slug" title="A slug is the part of a URL which identifies a page using human-readable keywords.">slug</a> is?') ?>)</span>
		<?php echo $form->error($model,'slug'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'currency'); ?>
		<?php echo $form->textField($model,'currency',array('size'=>5,'maxlength'=>5)); ?><br /><span class="hint">(<?php echo Yii::t('delt', 'You must provide a three-letter <a href="http://en.wikipedia.org/wiki/ISO_4217" title="Find more on Wikipedia">ISO 4217 code</a>, like EUR, USD, or GBP')?>)</span>
		<?php echo $form->error($model,'currency'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'language_id'); ?>
     <?php echo $form->dropDownList($model,'language_id', CHtml::listData(Language::model()->findAllSorted(),
        'id', //this is the attribute name for list option values 
        'complete_name' // this is the attribute name for list option texts 
         )
      ); ?>
    <br />
    <span class="hint">(<?php echo Yii::t('delt', 'The language is used for the names of the accounts, not for the user interface')?>)</span>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'languages'); ?>
    
     <?php echo $form->listBox($model,'languages', CHtml::listData(Language::model()->findAllSorted(), 'id', 'complete_name'), array('multiple'=>'multiple', 'width'=>500, 'options'=>$languages_options)) ?>
	</div>
  
  <?php if(!$model->id): ?>
    <div class="row checkbox">
      <?php echo $form->label($model, 'license') ?>
      <?php echo $form->checkBox($model, 'license_confirmation') ?>&nbsp;
      <?php echo Yii::t('delt', 'I agree on the fact that the contents of the firm I\'m creating will be available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.', array('{locale}'=>Yii::app()->language)) ?>
    </div>
  <?php endif ?>
  
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('delt', 'Create') : Yii::t('delt', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
