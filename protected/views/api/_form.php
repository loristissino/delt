<?php if(!$this->apiuser || !$this->apiuser->is_active): ?>

  <p><?php echo Yii::t('delt', 'You have not activated an API key.') ?></p>
  <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'ApiKeyForm',
    'enableAjaxValidation'=>false,
    'method'=>'POST',
    'action'=>$this->createUrl('api/subscribe'),
  )); ?>

    <div class="row">
      <?php echo CHtml::submitButton(Yii::t('delt', 'Subscribe')) ?>
    </div>

  <?php $this->endWidget() ?>

<?php else: ?>
  <p><?php echo Yii::t('delt', 'Your API key is active.') ?></p>
  <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'ApiKeyForm',
    'enableAjaxValidation'=>false,
    'method'=>'POST',
    'action'=>$this->createUrl('api/unsubscribe'),
  )); ?>

    <div class="row">
      <?php echo CHtml::submitButton(Yii::t('delt', 'Unsubscribe'), array('class'=>'dangerous') )?>
    </div>

  <?php $this->endWidget() ?>

<?php endif ?>
