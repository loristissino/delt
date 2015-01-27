<?php
/* @var $this TemplateController */
/* @var $model Template */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerCssFile(Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets')).'/gridview/styles.css'); 

$money_icon = $this->createIcon('money', Yii::t('delt', 'Amount'), array('width'=>16, 'height'=>16));

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'template-_template-form',
  'enableAjaxValidation'=>false,
)); ?>


  <p class="note"><?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?></p>
  <?php echo $form->errorSummary($model); ?>

  <div class="grid-view">
  <table class="items">
  <tr>
    <th id="firm-grid_c0"><?php echo Yii::t('delt', 'Method') ?></th>
    <th><?php echo Yii::t('delt', 'Account') ?></th>
    <th><?php echo Yii::t('delt', 'Debit') ?></th>
    <th><?php echo Yii::t('delt', 'Credit') ?></th>
    <th><?php echo Yii::t('delt', 'Comment') ?></th>
  </tr>
  <?php
    $methods=Template::model()->getMethods();  $row=0;
    foreach($model->postings as $posting): $row++?>
    <tr class="<?php echo $row%2==0 ? 'even': 'odd' ?>">
      <td>
      <?php echo CHtml::dropDownList(
        'method['.$posting['account_id'] . ']',
        $posting['account_id']==Yii::app()->getUser()->getState('last_account_closed_interactively')?'?':'$', 
        $methods) ?>
      </td>
      <td style="width: 300px">
      <?php echo $posting['account_name'] ?>
      <?php
        echo CHtml::hiddenField(
        'amount['.$posting['account_id'] . ']',
        $posting['amount'])
      ?>
      <?php
        echo CHtml::hiddenField(
        'comment['.$posting['account_id'] . ']',
        $posting['comment'])
      ?>
      </td>
      <td style="text-align: center">
        <?php if($posting['amount']>0) echo $money_icon ?>
      </td>
      <td style="text-align: center">
        <?php if($posting['amount']<0) echo $money_icon ?>
      </td>
      <td>
      <?php echo $posting['comment'] ?>
      </td>
    </tr>
  <?php endforeach ?>
  </table>
  <div class="row">
    <?php echo $form->labelEx($model,'description'); ?>
    <?php echo $form->textField($model,'description', array('size'=>100)); ?>
    <?php echo $form->error($model,'description'); ?>
  </div>

  <div class="row checkbox">
    <?php echo $form->label($model, 'automatic') ?>
    <?php echo $form->checkBox($model, 'automatic') ?>&nbsp;
    <?php echo Yii::t('delt', 'Apply this template automatically.') ?>
  <br />
  <span class="hint"><?php echo Yii::t('delt', 'This is useful for some closing entries you could wish to be automatically and virtually prepared each time a financial statement is prepared (without actually seeing the journal entry).') ?></span>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton(Yii::t('delt', 'Create')); ?>
  </div>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
