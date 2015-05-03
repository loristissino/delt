<?php
/* @var $this ChallengeController */
/* @var $data Challenge */

?>

<div class="view challenge <?php echo $data->getStatus() ?>">

  <div class="challenge">

    <?php if ($data->isOpen()): ?>
      <?php echo $this->createIcon('book_open', Yii::t('delt', 'Open Challenge'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'The running challenge'))); ?>
    <?php else: ?>
      <?php echo $this->createIcon('book', Yii::t('delt', 'Challenge'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'A challenge'))); ?>
    <?php endif ?>
    <?php echo CHtml::encode($data->exercise); ?>
    <br />
    
    <?php /*

    <b><?php echo CHtml::encode($data->getAttributeLabel('instructor_id')); ?>:</b>
    <?php echo CHtml::encode($data->instructor_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
    <?php echo CHtml::encode($data->user_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('firm_id')); ?>:</b>
    <?php echo CHtml::encode($data->firm); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('assigned_at')); ?>:</b>
    <?php echo CHtml::encode($data->assigned_at); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('started_at')); ?>:</b>
    <?php echo CHtml::encode($data->started_at); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('suspended_at')); ?>:</b>
    <?php echo CHtml::encode($data->suspended_at); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('completed_at')); ?>:</b>
    <?php echo CHtml::encode($data->completed_at); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('method')); ?>:</b>
    <?php echo CHtml::encode($data->method); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('mark')); ?>:</b>
    <?php echo CHtml::encode($data->mark); ?>
    <br />

    */ ?>
    
  </div>

<div class="form" style="width: 700px">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'challengeform_'. $data->id,
  'enableAjaxValidation'=>false,
  'action'=>array('changestatus', 'id'=>$data->id),
)); ?>

<div class="actions buttons">
  <?php if (!$data->isStarted()): ?>
    <?php echo CHtml::submitButton(Yii::t('delt', 'Start'), array('name'=>'start', 'id'=>'start_button_'.$data->id)); ?>
  <?php elseif (!$data->isCompleted()): ?>
    <?php if ($data->isSuspended()): ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Resume'), array('name'=>'resume', 'id'=>'resume_button_'.$data->id)); ?>
    <?php else: ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Suspend'), array('name'=>'suspend', 'id'=>'suspend_button_'.$data->id)); ?>
      <?php if($data->hasFirm()): ?>
        <?php echo CHtml::submitButton(Yii::t('delt', 'Mark Completed'), array('name'=>'completed', 'id'=>'completed_button', 'confirm'=>Yii::t('delt', 'Are you sure?'))); ?>
      <?php endif ?>
    <?php endif ?>
  <?php else: ?>
    <?php echo CHtml::submitButton(Yii::t('delt', 'Check'), array('name'=>'check', 'id'=>'check_button_'.$data->id, 'class'=>'checkButton', 'data-id'=>$data->id)); ?>
  <?php endif ?>

  <?php if($data->isOpen()): ?>
    <?php if($data->firm): ?>
      <?php echo CHtml::link($data->firm, array('bookkeeping/journal', 'slug'=>$data->firm->slug), array('title'=>Yii::t('delt', 'Go to the journal of the firm «%name%»', array('%name%'=>$data->firm->name)))) ?>
    <?php else: ?>
      <?php echo CHtml::link(Yii::t('delt', 'Begin'), array('bookkeeping/index'), array('title'=>Yii::t('delt', 'Start the challenge by forking a firm'))) ?>
    <?php endif ?>
  <?php endif ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

</div><!-- challenge view -->
