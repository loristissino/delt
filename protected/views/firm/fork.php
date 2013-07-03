<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
	'Firms'=>array('index'),
	'Fork',
);

$available_firms = $this->DEUser->profile->allowed_firms - sizeof($this->DEUser->firms);

?>

<h1><?php echo Yii::t('delt', 'Fork an existing firm') ?></h1>

<?php if($available_firms <= 0): ?>
  <?php echo $this->renderPartial('/firm/_available') ?>
<?php else: ?>
  <?php echo $this->renderPartial('_firms', array('title'=>'Public firms', 'firms'=>$publicfirms)) ?>

  <?php if(sizeof($ownfirms)): ?>
    <?php echo $this->renderPartial('_firms', array('title'=>'Your firms', 'firms'=>$ownfirms)) ?>
  <?php endif ?>
  
  <h2><?php echo Yii::t('delt', 'A firm you know the slug of') ?></h2>

  <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'chosefirmform',
    'enableAjaxValidation'=>false,
    'method'=>'GET',
    'action'=>$this->createUrl('firm/prefork'),
  )); ?>

    <div class="row">
      <?php echo CHtml::label('slug', false) ?>
      <?php echo CHtml::textField('slug', '', array('size'=>40)) ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Fork'), array('name'=>'fork')) ?>
    </div>

  <?php $this->endWidget() ?>
  
<?php endif ?>

