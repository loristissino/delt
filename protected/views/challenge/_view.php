<?php
/* @var $this ChallengeController */
/* @var $data Challenge */
?>

<div class="view">

  <div class="challenge <?php echo $data->getStatus() ?>">

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
  'id'=>'challengeform',
  'enableAjaxValidation'=>false,
  'action'=>array('changestatus', 'id'=>$data->id),
)); ?>

<div class="actions buttons">
  <?php if (!$data->isStarted()): ?>
    <?php echo CHtml::submitButton(Yii::t('delt', 'Start'), array('name'=>'start', 'id'=>'start_button')); ?>
  <?php elseif (!$data->isCompleted()): ?>
    <?php if ($data->isSuspended()): ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Resume'), array('name'=>'resume', 'id'=>'resume_button')); ?>
    <?php else: ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Suspend'), array('name'=>'suspend', 'id'=>'suspend_button')); ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Mark Completed'), array('name'=>'completed', 'id'=>'completed_button', 'confirm'=>Yii::t('delt', 'Are you sure?'))); ?>
    <?php endif ?>
  <?php endif ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

</div><!-- challenge view -->
