<?php
/* @var $this EventController */
/* @var $model Event */

$this->breadcrumbs=array(
  'Events'=>array('admin'),
  'Delete Old Events',
);

$this->menu=array(
  array('label'=>'Manage Event', 'url'=>array('admin')),
);
?>

<h1>Delete Old Events</h1>

<p>There are <?= $number ?> old events to delete.</p>

<?php if ($number>0): ?>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'DeleteOldEventsForm',
        'enableAjaxValidation'=>false,
        'method'=>'POST'
    )); ?>

    <div class="row">
      <?php echo CHtml::submitButton(Yii::t('delt', 'Delete Old Events'), array('name'=>'Delete')) ?>
    </div>

    <?php $this->endWidget() ?>

<?php endif ?>
