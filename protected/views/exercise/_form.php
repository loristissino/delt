<?php
/* @var $this ExerciseController */
/* @var $model Exercise */
/* @var $form CActiveForm */

//$regenarate_anchor = addslashes(CHtml::link(Yii::t('delt', 'regenerate'), '#', array('id'=>'regenerate', 'title'=>Yii::t('delt', 'Click here if you want to regenerate the slug from the name of the firm'))));

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/slugifier.js', CClientScript::POS_END);
$cs->registerScript(
  'slugify-handler',
  '
    
  var slugified = ' . ($model->slug ? 'true':'false') . ';
  
  function slugit()
  {
     $("#Exercise_slug").val($("#Exercise_title").val().slugify().substring(0,32));
  }
  
  $("#Exercise_title").keyup(function()
    {
      if(!slugified)
      {
        slugit();
      }
    }
  );
  
  $("#Exercise_slug").change(function()
    {
      if ($("#Exercise_slug").val()!="")
        slugified=true;
    }
  );
  
  '
  ,
  CClientScript::POS_READY
);

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'exercise-form',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // There is a call to performAjaxValidation() commented in generated controller code.
  // See class documentation of CActiveForm for details on this.
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?></p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'firm_id'); ?>
       <?php echo $form->dropDownList(
          $model, 
          'firm_id',
          CHtml::listData($this->DEUser->firms, 'id', 'name')
        )
       ?>
    <?php echo $form->error($model,'firm_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'title'); ?>
    <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'title'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'slug'); ?>
    <?php echo $form->textField($model,'slug',array('size'=>32,'maxlength'=>32)); ?>
    <?php echo $form->error($model,'slug'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'description'); ?>
    <?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'description'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'introduction'); ?><span class="hint"><?php echo Yii::t('delt', 'You can use Markdown syntax in this field.') ?></span>
    <?php echo $form->textArea($model,'introduction',array('rows'=>6, 'cols'=>50)); ?>
    <?php echo $form->error($model,'introduction'); ?>
  </div>
  
  <div class="row checkbox">
    <?php echo $form->label($model, 'method') ?>
    <?php foreach($model->method_items as $key=>$value): ?>
      <?php echo $form->checkBox($model, 'method_items['.$key.']', array('checked'=>$value['value']!=0)) ?>&nbsp;
      <?php echo Yii::t('delt', $value['label']) ?><br />
    <?php endforeach ?>
  </div>

  <?php if(!$model->id): ?>
    <div class="row checkbox">
      <?php echo $form->label($model, 'license') ?>
      <?php echo $form->checkBox($model, 'license_confirmation') ?>&nbsp;
      <?php echo Yii::t('delt', 'I agree on the fact that the contents of the exercise I\'m creating will be available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}" target="_blank">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.', array('{locale}'=>Yii::app()->language)) ?>
    <br />
    <span class="hint">(<?php echo Yii::t('delt', 'Curious about <a href="{url}" target="_blank">why</a> you have to accept a Creative Commons License?', array('{url}'=>$this->createUrl('site/en/cclicense'))) ?>)</span>
    </div>
  <?php endif ?>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('delt', 'Create') : Yii::t('delt', 'Save')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
